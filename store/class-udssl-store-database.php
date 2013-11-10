<?php
/**
 * UDSSL Store Database
 */
class UDSSL_Store_Database{
    /**
     * UDSSL Sales Table
     */
    private $sales_table;

    /**
     * Construct the interface
     */
    function __construct(){
        $this->sales_table = 'udssl_sales';
    }

    /**
     * Create Sales Table
     */
    function create_sales_table(){
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $sales_table = $wpdb->prefix . $this->sales_table;
        $sales_sql = "CREATE TABLE IF NOT EXISTS $sales_table (
            id int not null auto_increment primary key,
            transaction_id varchar(60) not null,
            item_id varchar(40) not null,
            create_time datetime not null,
            update_time datetime not null,
            state varchar(60) not null,
            amount int default 0,
            token varchar(40) not null,
            ip varchar(40) not null,
            email varchar(50) not null,
            first_name varchar(30) not null,
            last_name varchar(30) not null,
            payer_id varchar(30) not null,
            count int default 0,
            description varchar(80) not null,
            link varchar(300) not null
        )
        engine = InnoDB default character set = utf8 collate = utf8_unicode_ci;";
        $r = dbDelta($sales_sql);
    }

    /**
     * UDSSL PayPal SetExpressCheckout
     */
    function paypal_set_record($cart, $token){
        global $wpdb;
        $table_name = $wpdb->prefix . $this->sales_table;
        foreach($cart as $item){
            $record = array(
                'transaction_id' => $item['transaction_id'],
                'item_id' => $item['id'],
                'create_time' => current_time('mysql'),
                'update_time' => current_time('mysql'),
                'state' => 'Created',
                'amount' => $item['amount'] * 100,
                'description' => $item['name'],
                'token' => $token,
                'first_name' => 'None',
                'last_name' => 'None',
                'email' => 'None',
                'payer_id' => 'None',
                'count' => $item['quantity'] * 5,
                'ip' => $_SERVER['REMOTE_ADDR'],
                'link' => ''
            );
            $r = $wpdb->insert( $table_name, $record);
        }
        if(!$r):
            wp_die('Database Error!');
        endif;
        return true;
    }

    /**
     * UDSSL Check Token
     */
    function check_token($token){
        global $wpdb;
        $table_name = $wpdb->prefix . $this->sales_table;
        $where = ' token like "' . $token . '"';
        $record = $wpdb->get_results(
            "
            SELECT *
            FROM $table_name
            WHERE $where
            ", ARRAY_A
        );

        if(!$record)
            return false;

        return true;
    }


    /**
     * UDSSL PayPal Checkout Complete
     */
    function checkout_complete($getEC, $item_id, $download_link){
        $raw_data['state'] = 'Paid';
        $raw_data['first_name'] = $getEC->PayerInfo->PayerName->FirstName;
        $raw_data['last_name'] = $getEC->PayerInfo->PayerName->LastName;
        $raw_data['payer_email'] = $getEC->PayerInfo->Payer;
        $raw_data['payer_id'] = $getEC->PayerInfo->PayerID;
        $raw_data['token'] = $getEC->Token;
        $raw_data['link'] = $download_link;
        $this->add_record($raw_data, $item_id);
    }

    /**
     * Add Record
     */
    function add_record($raw_data, $item_id){
        global $wpdb;
        $table_name = $wpdb->prefix . $this->sales_table;
        $r = $wpdb->query( $wpdb->prepare(
            "
            UPDATE $table_name
            SET state = '%s',
            first_name = '%s',
            last_name = '%s',
            email = '%s',
            payer_id = '%s',
            link = '%s'
            WHERE token = '%s' AND item_id = '%s'
            ",
            $raw_data['state'],
            $raw_data['first_name'],
            $raw_data['last_name'],
            $raw_data['payer_email'],
            $raw_data['payer_id'],
            $raw_data['link'],
            $raw_data['token'],
            $item_id
        )
        );

        if(false === $r) {
            wp_die('Database Error!');
        }
        return true;
    }

    /**
     * PayPal IPN Complete
     */
    function paypal_do_ec($raw_data){
        $state = $raw_data['payment_status'];
        if($state == 'Completed'){
            $raw_data['payment_status'] = 'Paid (IPN)';
            $this->update_record($raw_data);
        } else {
            return false;
        }
    }

    /**
     * Update Record
     */
    function update_record($raw_data){
        $state = $raw_data['payment_status'];
        $transaction_id = $raw_data['invoice'];
        $first_name = $raw_data['first_name'];
        $last_name = $raw_data['last_name'];
        $payer_email = $raw_data['payer_email'];
        $payer_id = $raw_data['payer_id'];

        $update_time = date('Y-m-d H:i:s');

        global $wpdb;
        $table_name = $wpdb->prefix . $this->sales_table;
        $r = $wpdb->query( $wpdb->prepare(
            "
            UPDATE $table_name
            SET state = '%s',
            first_name = '%s',
            last_name = '%s',
            email = '%s',
            payer_id = '%s',
            update_time = '%s'
            WHERE transaction_id like '%s'
            ",
            $state,
            $first_name,
            $last_name,
            $payer_email,
            $payer_id,
            $update_time,
            $transaction_id
        )
        );
        if(false === $r) {
            wp_die('Database Error!');
        }

        $item_number = $raw_data['invoice'];
        $item_ids = explode('_', $item_number);
        $item_id = $item_ids[1];
        $this->send_email($first_name, $payer_id, $payer_email, $item_id, $transaction_id);
        return true;
    }

    /**
     * Send an email to the buyer
     */
    function send_email($getEC, $downloads){
        $info = array(
            'token' => $getEC->Token,
            'payerinfo' => array(
                'payer' => $getEC->PayerInfo->Payer,
                'payerid' => $getEC->PayerInfo->PayerID,
                'payername' => array(
                    'firstname' => $getEC->PayerInfo->PayerName->FirstName,
                    'lastname' => $getEC->PayerInfo->PayerName->LastName
                ),
                'invoiceid' => $getEC->InvoiceID
            )
        );

        $message  = '<h3>Hi, ' . $info['payerinfo']['payername']['firstname'] . ',</h3>';
        $message .= '<h4><i>Order: ' . $info['token'] . '_' . $info['payerinfo']['payerid'] . '</i></h4>';
        $message .= '<p>Your purchase is ready to download.</p>';
        $message .= '<hr />';

        foreach($downloads as $download){
            $title = ' title="Download ' . $download['name'] . '" ';
            $message  .= '<h2>' . $download['name'] . '</h2>';
            $message  .= '<p><a href="' . get_home_url() . '/downloads/' . $download['link'] . '/" ' . $title . ' >Download Now</a> (Quantity:' . $download['quantity'] .')</p>';
            $message .= '<hr />';
        }

        $message .= '<p><b>Note:</b> Each item can be downloaded only 5 times. If your purchased <b>quantity</b> is larger than one,
            then you can download <b>( quantity * 5 )</b> times.</p>';

        $message .= '<p>Thank you for the purchase.<br /><a href="http://udssl.com" >UDSSL</a> - USB Digital Services<br />praveen.udssl@gmail.com</p>';
        $message .= '<img src="' . UDS_URL . 'assets/udssl-logo.png" alt="UDSSL Logo" >';

        $to = $info['payerinfo']['payer'];
        $subject = 'UDSSL Store | Order #' . $info['token'] . ' | Your Download is Ready ';
        $headers[] = 'From: UDSSL <praveen.udssl@gmail.com>';
        $headers[] = 'Cc: UDSSL Sales <praveen.udssl@gmail.com>';

        /**
         * Customer and Sales Emails
         */
        add_filter( 'wp_mail_content_type', array($this, 'set_html_content_type') );
        $r = wp_mail($to, $subject, $message, $headers);

        /**
         * UDSSL PayPal Notification
         */
        $message = '<pre>' . print_r($getEC, true) . '</pre>';
        $headers = array();
        $headers[] = 'From: UDSSL PayPal <praveen.udssl@gmail.com>';
        $r = wp_mail('praveen.udssl@gmail.com', 'UDSSL PayPal', $message );

        remove_filter( 'wp_mail_content_type', array($this, 'set_html_content_type') );
        return $r;

    }

    /**
     * Set HTML Content Type
     */
    function set_html_content_type(){
        return 'text/html';
    }

    /**
     * Get Item Record
     */
    function get_item_record($transaction_id){
        global $wpdb;
        $table_name = $wpdb->prefix . $this->sales_table;
        $record = $wpdb->get_results( $wpdb->prepare(
            "
            SELECT *
            FROM $table_name
            WHERE transaction_id like '%s'
            ",
            $transaction_id
        ), ARRAY_A
        );

        if(!$record)
            return false;

        return $record[0];
    }

    /**
     * Get TransactionId by Token
     */
    function paypal_get_transaction_id_by_token($token){
        global $wpdb;
        $table_name = $wpdb->prefix . $this->sales_table;
        $record = $wpdb->get_results( $wpdb->prepare(
            "
            SELECT transaction_id
            FROM $table_name
            WHERE token = '%s'
            ",
            $token
        ), ARRAY_A
        );

        if(!$record)
            return false;

        return $record[0]['transaction_id'];
    }

    /**
     * Get Item ID by Link
     */
    function paypal_get_item_id_by_link($link){
        global $wpdb;
        $table_name = $wpdb->prefix . $this->sales_table;
        $record = $wpdb->get_results( $wpdb->prepare(
            "
            SELECT item_id, count
            FROM $table_name
            WHERE link = '%s'
            ",
            $link
        ), ARRAY_A
        );

        if(!$record)
            return false;

        if(0 == $record[0]['count']){
            wp_die('Allowed No of Downloads Exceeded');
        }

        return $record[0]['item_id'];
    }

    /**
     * Decrease Count by Link
     */
    function decrease_count_by_link($link){
        global $wpdb;
        $table_name = $wpdb->prefix . $this->sales_table;
        $record = $wpdb->query( $wpdb->prepare(
            "
            UPDATE $table_name
            SET count = count - 1
            WHERE link = '%s'
            ",
            $link
        )
        );
    }

    /**
     * Update Item Record
     */
    function update_item_record($record){
        global $wpdb;
        $table_name = $wpdb->prefix . $this->sales_table;
        $where = array( 'id' => $record['id'] );
        $r = $wpdb->update( $table_name, $record, $where);

        if(false === $r) {
            wp_die('Database Error!');
        }
        return true;
    }

    /**
     * Get all records
     */
    function get_all_records(){
        global $wpdb;
        $table_name = $wpdb->prefix . $this->sales_table;
        $records = $wpdb->get_results(
            "
            SELECT *
            FROM $table_name
            ", ARRAY_A
        );

        if(!$records)
            return false;

        return $records;
    }
}
?>
