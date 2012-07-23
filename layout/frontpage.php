<?php

$hasheading = ($PAGE->heading);
$hasnavbar = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar());
$hasfooter = (empty($PAGE->layout_options['nofooter']));
$hassidepre = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('side-pre', $OUTPUT));
$hassidepost = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('side-post', $OUTPUT));

$showsidepre = ($hassidepre && !$PAGE->blocks->region_completely_docked('side-pre', $OUTPUT));
$showsidepost = ($hassidepost && !$PAGE->blocks->region_completely_docked('side-post', $OUTPUT));
$custommenu = $OUTPUT->custom_menu();
$hascustommenu = (empty($PAGE->layout_options['nocustommenu']) && !empty($custommenu));

$bodyclasses = array();
if ($showsidepre && !$showsidepost) {
    $bodyclasses[] = 'side-pre-only';
} else if ($showsidepost && !$showsidepre) {
    $bodyclasses[] = 'side-post-only';
} else if (!$showsidepost && !$showsidepre) {
    $bodyclasses[] = 'content-only';
}
if ($hascustommenu) {
    $bodyclasses[] = 'has_custom_menu';
}

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes() ?>>
<head>
    <title><?php echo $PAGE->title ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->pix_url('favicon', 'theme')?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
</head>
<body id="<?php p($PAGE->bodyid) ?>" class="<?php p($PAGE->bodyclasses.' '.join(' ', $bodyclasses)) ?>">
<?php echo $OUTPUT->standard_top_of_body_html() ?>

<div id="page">
    <div id="wrapper" class="clearfix">
        <?php if ($hasheading || $hasnavbar) { ?>

        <div id="page-header-banner" class="clearfix">
            <div id="page-header" class="clearfix">
                <div id="page-header-right" class="clearfix">

                    <?php if ($hasheading) { ?>
                    <div class="headingtext">
                        <h1 class="headermain"><?php echo $PAGE->heading ?></h1>
                        <h2 class="department">
                            <?php
                            global $DB;   //makes sure the database is available
                            $category = $DB->get_record('course_categories',array('id'=>$COURSE->category));
                            if(empty($category))  {echo "Unitec eLearning Platform";
                            }else{ //gets the database record from the course_categories table for the active course
                                $cats=explode("/",$category->path);    // splits the category path into an array so that each level in the categories is a different level in the array
                                $depth=1;    // what depth of sub-category you want to display: Note this may need some error trapping to ensure there are that many levels of subcategories for the course - or setting a default value if not
                                $categorydepth = $DB->get_record("course_categories", array("id" => $cats[$depth]) );    //gets the database record for the course_category with the id number set by $depth position in the array of the category path for the active path
                                $categoryname = $categorydepth->name;    //sets a variable name for the set depth of subcategory ready to be displayed as required
                                echo $categoryname;
                            }
                            ?>
                        </h2>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="headermenu">
            <?php
            echo $OUTPUT->login_info();
            echo $PAGE->headingmenu
            ?>
        </div>

        <?php if ($hascustommenu) { ?>
            <div id="custommenu"><?php echo $custommenu; ?></div>
            <?php } ?>

        <?php if ($hasnavbar) { ?>
            <div class="navbar clearfix">
                <div class="breadcrumb"><?php echo $OUTPUT->navbar(); ?></div>
                <div class="navbutton"> <?php echo $PAGE->button; ?></div>
            </div>
            <?php } ?>

        <?php } ?>

        <!-- END OF HEADER -->

        <div id="page-content-wrapper">
            <div id="page-content">
                <div id="region-main-box">
                    <div id="region-post-box">

                        <div id="region-main-wrap">
                            <div id="region-main">
                                <div class="region-content">
                                    <?php echo $OUTPUT->main_content() ?>
                                </div>
                            </div>
                        </div>

                        <?php if ($hassidepre) { ?>
                        <div id="region-pre" class="block-region">
                            <div class="region-content">
                                <?php echo $OUTPUT->blocks_for_region('side-pre') ?>
                            </div>
                        </div>
                        <?php } ?>

                        <?php if ($hassidepost) { ?>
                        <div id="region-post" class="block-region">
                            <div class="region-content">
                                <?php echo $OUTPUT->blocks_for_region('side-post') ?>

                            </div>
                        </div>
                        <?php } ?>

                    </div>
                </div>    <div align="center"><?php global $COURSE; if($COURSE->id == 1) print_course_search(); ?><br></div>
            </div>
        </div>

    </div>

    <!-- START OF FOOTER -->
    <?php if ($hasfooter) { ?>
    <div id="page-footer">
        <div class="footerleft">
            <div class="footerright">
                <p class="helplink"><?php echo page_doc_link(get_string('moodledocslink')) ?></p>
                <?php
                echo $OUTPUT->login_info();
                echo $OUTPUT->home_link();
                echo $OUTPUT->standard_footer_html();
                if (!empty($PAGE->layout_options['langmenu'])) {
                    echo $OUTPUT->lang_menu();
                }
                ?>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>