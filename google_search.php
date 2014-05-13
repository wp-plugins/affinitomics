<?php
/*
Affinitomics (Wordpress Plugin)
Copyright (C) 2014 Prefrent
*/
/*

    ----------------------------------------------------------------------
    Include Usage Example
    ----------------------------------------------------------------------
    <?php include(ABSPATH.'/wp-content/plugins/affinitomics/google_search.php'); ?>

    ----------------------------------------------------------------------
    Search HTML Produced by Google CSE: 
    ----------------------------------------------------------------------
    <div id="af-search">
        <h2>Search Using Affinitomic Profile:</h2>
        <form action="" method="post" name="afsearch">
            <input type="hidden" name="a" id="a" value="%22nokia%22+%22microsoft%22+-%22apple%22+-%22google%22+-%22tim+cook%22">
            <input type="text" name="q" id="q" value="joe"> 
            <input type="submit">
        </form>
        <ul id="search-content">
            <li><a href="#">result 1</a></li>
            <li><a href="#">result 2</a></li>
            <li><a href="#">result 3</a></li>
            <li><a href="#">result 4</a></li>
            <li><a href="#">result 5</a></li>
            <li><a href="#">result 6</a></li>
            <li><a href="#">result 7</a></li>
        </ul>
    </div>

    ----------------------------------------------------------------------
    CSS Styling Examples:
    ----------------------------------------------------------------------
    #af-search h2 {background-color:magenta;}
    #search-content  {background-color:green;}

*/
?>

    <?php /* GOOGLE CUSTOM SEARCH JAVASCRIPT VARS */ 

    ?>
    <script>
        var google_cse_version = 'google_search.php';
        var cx = '<?php echo get_option('af_google_cse_id'); ?>';
        var key = '<?php echo get_option('af_google_cse_key'); ?>';
        <?php
            $q = '';
            if (isset($_REQUEST['q'])) {
                $q = htmlspecialchars(strip_tags($_REQUEST['q']));
                echo 'var q = "' . $q . '";';
            } else {
                echo 'var q = "";';
            }
            $a = '';
            if (isset($_REQUEST['a'])) {
                $a = htmlspecialchars(strip_tags($_REQUEST['a']));
                echo 'var a = "' . $a . '";';
            } else {
                echo 'var a = "";';
            }
        ?>
    </script>
    <?php
        //https://developers.google.com/custom-search/v1/using_rest#WorkingResults
        if (0) {
            echo '<h1>$_REQUEST:</h1><pre>';
            print_r($_REQUEST);
            echo '</pre>';
        }
        $post_id = get_the_ID();

        // Get Affinitomic Meta Data
        $descriptors_meta = get_post_meta($post_id, '_afpost_descriptors', true);
        $draw_meta = get_post_meta($post_id, '_afpost_draw', true);
        $distance_meta = get_post_meta($post_id, '_afpost_distance', true);

        // Use Meta Data to Build Affinitomic Search String
        $affinitomics = '';
        if ($descriptors_meta != '') {
            $affinitomics = $descriptors_meta; 
        }
        if ($draw_meta != '') {
            if ($affinitomics == '') { 
                $affinitomics = $draw_meta; 
            } else {
                $affinitomics .= ', ' . $draw_meta; 
            }
        }
        if ($distance_meta != '') {
            if ($affinitomics == '') { 
                $affinitomics = $distance_meta; 
            } else {
                $affinitomics .= ', ' . $distance_meta; 
            }
        }

        // Clean Up Search String For Google
        $affinitomics = str_replace(range(0,9),'',$affinitomics);
        $affinitomics = str_replace('+','',$affinitomics);
        $affinitomics = str_replace(',','%22%22',$affinitomics);
        $affinitomics = str_replace('%22 ','%22',$affinitomics);
        $affinitomics = str_replace('%22%22','%22+%22',$affinitomics);
        $affinitomics = str_replace(' ','+',$affinitomics);
        $affinitomics = str_replace('%22-','-%22',$affinitomics);
        $affinitomics = '%22' . $affinitomics . '%22'; 

    ?>

    <?php if ($affinitomics != '') { ?>
    <div>&nbsp;</div>
    
    <div id="af-search">
        <h2>Search Using Affinitomic Profile:</h2>
        <form action="" method="post" name="afsearch">
            <input type="hidden" name="a" id="a" value="<?php echo $affinitomics; ?>" />
            <input type="text" name="q" id="q" value="<?php echo $q; ?>"/> 
            <input type="submit"/>
        </form>
        
        <ul id="search-content"></ul>
        
        <pre id="dump"></pre>
        <?php } ?>

        <?php if (isset($_REQUEST['q'])) { ?>
        <script>
            function gcs(response) {
              //console.log(JSON.stringify(response.searchInformation));
              if ((typeof response != 'undefined') && (response.searchInformation.totalResults > 0)){
                for (var i = 0; i < response.items.length; i++) {
                    var item = response.items[i];
                    document.getElementById("search-content").innerHTML += "<li><a href='" + item.link + "'>" + item.htmlTitle + "</a></li>";
                }
              } else {
                    document.getElementById("search-content").innerHTML += "<li>No results found.</li>";
              }
            }
            document.write("<script src='"+"https://www.googleapis.com/customsearch/v1?key="+key+"&cx="+cx+"&q="+q+" "+a+"&callback=gcs"+"'><\/script>");
        </script>
        <?php } ?>
    </div><!-- af-search -->

    <nav class="nav-single">
        <h3 class="assistive-text"><?php _e( 'Post navigation', 'twentytwelve' ); ?></h3>
        <span class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'twentytwelve' ) . '</span> %title' ); ?></span>
        <span class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'twentytwelve' ) . '</span>' ); ?></span>
    </nav><!-- .nav-single -->
