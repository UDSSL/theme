<?php
/**
 * UDSSL Theme Search Page
 */
get_header();
global $udssl_theme;
?>
<div class="row">
    <div class="col-md-8">
        <h1>Search Results</h1>
        <p class="text-muted"><i>UDSSL Search Results powered by Google Custom Search</i></p>
        <div>
        <script>
          (function() {
            var cx = '002712191060810368479:bfayxxelg-q';
            var gcse = document.createElement('script');
            gcse.type = 'text/javascript';
            gcse.async = true;
            gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
                '//www.google.com/cse/cse.js?cx=' + cx;
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(gcse, s);
          })();
        </script>
        <gcse:searchresults-only></gcse:searchresults-only>
        </div>
    </div>
    <div class="col-md-4">
        <?php $udssl_theme->sidebar->single_right(); ?>
    </div>
</div>
<?php
get_footer();
