<?php
/**
 * Header Utilities
 */
class UDSSL_Header{
    function top_navigation() {
        if(is_user_logged_in()){
            $current_user = wp_get_current_user();
            $member = '
             <!-- Split button -->
            <div class="btn-group">
              <a href="' . get_home_url() . '/profile/" type="button" class="btn btn-default">' . $current_user->user_login . '</a>
              <a href="' . get_home_url() . '/profile/" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="' . get_home_url() . '/profile/">Edit Profile</a></li>
                <li><a href="' . get_home_url() . '/membership/">Membeship</a></li>
                <li><a href="' . get_home_url() . '/cart/">Cart</a></li>
                <li class="divider"></li>
                <li><a href="' . get_home_url() . '/logout/">Logout</a></li>
              </ul>
            </div>
                ';
        } else {
        $member = '
            <a href="' . get_home_url() . '/signup/" class="btn btn-success">Sign Up Free</a>
            <a href="' . get_home_url() . '/login/" class="btn btn-default">Login</a>
            ';
        }

        return '<div class="navbar navbar-default navbar-shadow navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".udssl-main">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="' . get_home_url() . '">UDSSL</a>
        </div>
        <div class="navbar-collapse collapse udssl-main">
          <ul class="nav navbar-nav">
            <li><a rel="nofollow" href="' . get_home_url() . '/about/">About</a></li>
            <li><a href="' . get_home_url() . '/contact/">Contact</a></li>
            <li class="dropdown">
              <a href="' . get_home_url() . '/projects/" class="dropdown-toggle" data-toggle="dropdown">Projects <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a rel="nofollow" href="' . get_home_url() . '/udssl-theme/">UDSSL Theme</a></li>
                <li><a href="' . get_home_url() . '/udssl-time-tracker/">UDSSL Time Tracker</a></li>
                <li class="divider"></li>
                <li class="dropdown-header">Control Engineering</li>
                <li><a href="' . get_home_url() . '/projects/water-level-control-system/">Computer Aided Water Level Control</a></li>
                <li><a href="' . get_home_url() . '/projects/remote-and-shared-control/">Remote and Networked Control</a></li>
              </ul>
            <li class="dropdown">
              <a href="' . get_home_url() . '/udssl-now-reading/" title="Now Reading | Premium WordPress Plugin" class="dropdown-toggle" data-toggle="dropdown">Now Reading <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="' . get_home_url() . '/udssl-now-reading/">Introduction to UDSSL Now Reading</a></li>
                <li><a href="' . get_home_url() . '/udssl-now-reading/now-reading-screenshots/">Now Reading Plugin Screenshots</a></li>
                <li class="divider"></li>
                <li class="dropdown-header">Live Preview</li>
                <li><a href="' . get_home_url() . '/udssl-now-reading/now-reading-demonstration/">Demonstration of Now Reading Widget</a></li>
              </ul>
            </li>
          </ul>
          <ul class="navbar-form navbar-right">' . $member . '</ul>
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
