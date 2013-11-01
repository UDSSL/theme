<?php
/**
 * UDSSL 404 Page
 */
get_header();
global $udssl_theme;
?>
<div class="row">
    <div class="col-md-8">
    <div id="content" class="narrowcolumn">
        <div class="alert alert-warning"><strong>Error</strong> The Page Not Found</div>
        <img class="img-centered img-responsive " src="<?php echo UDS_URL; ?>assets/404.png" />
   </div>
    <div class="row">
        <div class="col-md-12">
        <hr />
        <h2>UDSSL Time Tracker</h2>
        <h4 class="text-muted"><i>The WordPress Time Tracker</i></h4>
        <p class="text-info"> <b>UDSSL Time Tracker</b> helps you to <b>precisely</b> track your time. Charts allows you to <b>visualize</b> how your time is spent and helps you to be more productive.</p>
        <p><i>UDSSL Time Tracker</i> helps you to track your time easily with <i>an intuitive interface</i>. You
        can easily track your time <i>with a few clicks</i>. Using presets you can track frequent tasks instantly.
        Once you setup your tasks, projects and categories, you only have to enter an optional description
        on how a particular time period is spent.</p>
        <a href="<?php echo get_home_url(); ?>/udssl-time-tracker/" title="Read more about UDSSL Time Tracker" class="btn btn-primary btn-lg">UDSSL Time Tracker</a>
        <p class="text-info">Visit <a href="<?php echo get_home_url(); ?>/udssl-time-tracker/" ><b>UDSSL Time Tracker</b></a> Now!</p>
        <hr />
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
        <h2>UDSSL Search</h2>
        <h4 class="text-muted"><i>Search UDSSL with Google</i></h4>
        <form class="form-inline text-left" role="form" id="searchForm" action="<?php echo home_url(); ?>/search/" method="GET">
          <div class="form-group">
            <input type="text" class="form-control input-lg" name="q" id="search-text" placeholder="USB Solutions">
          </div>
          <button type="submit" class="btn btn-default btn-lg">Search</button>
        </form>
        <hr />
        </div>
    </div>

    </div>
    <div class="col-md-4">
        <?php $udssl_theme->sidebar->single_right(); ?>
    </div>
</div>
<?php get_footer(); ?>
