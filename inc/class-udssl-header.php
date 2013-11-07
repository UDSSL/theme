<?php
/**
 * Header Utilities
 */
class UDSSL_Header{
    function top_navigation() {
        if(is_user_logged_in()){
            $current_user = wp_get_current_user();
            $member = '
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">' . $current_user->user_login . ' <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="' . get_home_url() . '/profile/">' . $current_user->user_login . '</a></li>
                    <li><a href="' . get_home_url() . '/membership/">Membership</a></li>
                    <li><a href="' . get_home_url() . '/logout/">Logout</a></li>
                </ul>
            </li>
                ';
        } else {
        $member = '
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user fa-1x"></i> Login <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="' . get_home_url() . '/login/"><i class="fa fa-sign-in"></i> Login</a></li>
                    <li><a href="' . get_home_url() . '/signup/"><i class="fa fa-credit-card"></i> Sign Up Free</a></li>
                </ul>
            </li>
            ';
        }

        return '
            <div class="navbar navbar-default navbar-shadow navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".udssl-main">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="' . get_home_url() . '"><i class="fa fa-home"></i> Home</a>
                </div>
                <div class="navbar-collapse collapse udssl-main">
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <!-- ---------------------- USB ---------------------------------------- -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-thumbs-up"></i> USB <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Universal Serial Bus</a></li>
                                <li class="divider"></li>
                                <li class="dropdown-header">USB History</li>
                                <li><a href="' . get_home_url() . '/usb-history/">USB History</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <!-- ---------------------- Control ---------------------------------------- -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cogs"></i> Control <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="' . get_home_url() . '/computer-aided-control-systems/">CAC Systems</a></li>
                                <li class="divider"></li>
                                <li class="dropdown-header">Simple Water Level Control</li>
                                <li><a href="' . get_home_url() . '/projects/water-level-control-system/">The CA WLC System</a></li>
                                <li><a href="' . get_home_url() . '/projects/remote-and-shared-control/">Remote and Networked Control</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <!-- ---------------------- Telecom ---------------------------------------- -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-globe"></i> Telecom <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="' . get_home_url() . '/telecommunications/">Telecommunications</a></li>
                                <li><a href="' . get_home_url() . '/switching-history/">Switching History</a></li>
                                <li><a href="' . get_home_url() . '/next-generation-networks/">Next Generation Networks</a></li>
                                <li class="divider"></li>
                                <li class="dropdown-header">Premium Content</li>
                                <li><a href="' . get_home_url() . '/sri-lanka-telecom/">Sri Lanka Telecom PLC</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <!-- ---------------------- WordPress ---------------------------------------- -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-trophy"></i> WordPress <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                            <li><a href="#">UDSSL Theme</a></li>
                            <li class="divider"></li>
                            <li class="dropdown-header">Now Reading WordPress Plugin</li>
                            <li><a href="' . get_home_url() . '/udssl-now-reading/">Introduction to UDSSL Now Reading</a></li>
                            <li><a href="' . get_home_url() . '/udssl-now-reading/now-reading-screenshots/">Now Reading Plugin Screenshots</a></li>
                            <li><a href="' . get_home_url() . '/udssl-now-reading/now-reading-demonstration/">Demonstration of Now Reading Widget</a></li>
                            <li class="divider"></li>
                            <li class="dropdown-header">WordPress Time Tracker Plugin</li>
                            <li><a href="' . get_home_url() . '/udssl-time-tracker/">Introduction to UDSSL Time Tracker</a></li>
                            <li><a href="' . get_home_url() . '/udssl-time-tracker/screenshots/">Time Tracker Screenshots</a></li>
                            <li><a href="' . get_home_url() . '/udssl-time-tracker/libraries-and-technologies/">Time Tracker Libraries and Technologies</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <!-- ---------------------- Misc ---------------------------------------- -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-flask"></i> Misc <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Vimrc File</a></li>
                            </ul>
                        </li>
                        <li><a href="' . get_home_url() . '/contact/"><i class="fa fa-comments"></i> Contact</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-shopping-cart"></i> Cart <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="#"><i class="text-muted">(Empty Cart)</i> 0.00 <i class="fa fa-usd"></i></a></li>
                                <li><a class="text-right" href="#"><i class="fa fa-credit-card"></i> Store</a></li>
                            </ul>
                        </li>
                        ' . $member . '
                    </ul>
                </div><!--/.navbar-collapse -->
            </div>
            </div>
            ';
    }

    function main_navigation() {
      return '<div class="navbar navbar-default">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".udssl-nav">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="' . get_home_url() . '">Home</a>
        </div>
        <div class="navbar-collapse collapse udssl-nav">
          <ul class="nav navbar-nav">
            <li><a rel="nofollow" href="' . get_home_url() . '/universal-serial-bus/">USB</a></li>
            <li><a rel="nofollow" href="' . get_home_url() . '/udssl-theme/">UDSSL Theme</a></li>
            <li><a href="' . get_home_url() . '/computer-aided-control-systems/">CACS</a></li>
            <li><a href="' . get_home_url() . '/udssl-time-tracker/">Time Tracker</a></li>
            <li><a href="' . get_home_url() . '/telecommunications/">Telecom</a></li>
            <li><a rel="nofollow" href="' . get_home_url() . '/store/">Store</a></li>
            <li><a href="' . get_home_url() . '/contact/">Contact</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>';
    }
}
?>
