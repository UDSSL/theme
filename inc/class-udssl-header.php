<?php
/**
 * Header Utilities
 */
class UDSSL_Header{
    function top_navigation() {
        return '<div class="navbar navbar-inverse navbar-fixed-top">
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
            <li class="active"><a href="' . get_home_url() . '/universal-serial-bus/">USB</a></li>
            <li><a href="' . get_home_url() . '/about/">About</a></li>
            <li><a href="' . get_home_url() . '/contact/">Contact</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Projects <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="' . get_home_url() . '/udssl-theme/">UDSSL Theme</a></li>
                <li><a href="' . get_home_url() . '/udssl-time-tracker/">UDSSL Time Tracker</a></li>
                <li class="divider"></li>
                <li class="dropdown-header">Control Engineering</li>
                <li><a href="' . get_home_url() . '/projects/water-level-control-system/">Computer Aided Water Level Control</a></li>
                <li><a href="' . get_home_url() . '/projects/remote-and-shared-control/">Remote and Networked Control</a></li>
              </ul>
            </li>
          </ul>
          <form class="navbar-form navbar-right" action="' . get_home_url() . '/sign-in/" method="POST">
            <div class="form-group">
              <input type="text" placeholder="Email" name="email" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" name="password" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Sign in</button>
          </form>
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
            <li><a href="' . get_home_url() . '/universal-serial-bus/">USB</a></li>
            <li><a href="' . get_home_url() . '/udssl-theme/">UDSSL Theme</a></li>
            <li><a href="' . get_home_url() . '/praveen-chathuranga-dias/">Praveen</a></li>
            <li><a href="' . get_home_url() . '/computer-aided-control-systems/">CACS</a></li>
            <li><a href="' . get_home_url() . '/udssl-time-tracker/">Time Tracker</a></li>
            <li><a href="' . get_home_url() . '/telecommunications/">Telecom</a></li>
            <li><a href="' . get_home_url() . '/contact/">Contact</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>';
    }
}
?>
