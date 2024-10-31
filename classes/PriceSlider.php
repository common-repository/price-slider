<?php

class PriceSlider {

    protected static $instance = false;
    protected static $options = false;
    protected $tcolor = 'e3e3e3';
    protected $bcolor = '000000';
    protected $title = "Price Slider";
    protected $content = 'Input contents here';
    protected $position = 'right';
    protected $type = "bottom";
    protected $show = 1;
    protected $pages = false;
    protected $posts = false;
    protected $amount = 50;
    protected $enabled = false;
    protected $mobile = false;
    protected $jQuerySent = false;
    protected $sliderSend = false;
    protected $width = 400;
    protected $height = 100;
    protected $labelHeight = 20;
    protected $admin = false;
    protected $slideMsecTime = 566;
    protected $sizeselect = 10;

    protected function __construct() {

        self::$options = array(
            'tcolor',
            'bcolor',
            'title',
            'content',
            'position',
            'enabled',
            'type',
            'amount',
            'show',
            'posts',
            'pages',
            'mobile',
            'sizeselect'
        );

        $this->_get();
        $this->_setHeight();
    }

    public function __clone() {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    /**
     * singleton..
     * @return PriceSlider
     */
    public static function instance() {
        if (!self::$instance) {
            $cxza = __CLASS__;
            self::$instance = new $cxza;
        }
        return self::$instance;
    }

    protected function _setHeight() {
        if ($this->sizeselect != null || (int) $this->sizeselect != 0) {
            if ((int) $this->sizeselect > 0 && (int) $this->sizeselect <= 41) {
                $this->height = (int) $this->sizeselect * 10;
            }
        }
    }

    protected function _get() {
        foreach (self::$options as $a => $b) {
            $this->_getOption($b);
        }

        return true;
    }

    protected function _getOption($name) {
        try {
            $new = get_option("price_slider_{$name}");
            if ($new) {
                $this->$name = $new;
            } else {
                $this->_updateOption($name, $this->$name);
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    protected function _updateOption($name, $value) {

        try {
            update_option("price_slider_{$name}", $value);
            $this->$name = $value;
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    public function process() {

        if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            $this->_update();
        }
        $this->_options();
        return true;
    }

    protected function _update() {

        $p = $_POST;

        foreach (self::$options as $a => $b) {
            if (isset($p[$b])) {
                $$b = $p[$b];
            } else {
                $$b = false;
            }
        }

        if ($tcolor)
            $tcolor = $this->_validateColor($p['tcolor']);

        if ($bcolor)
            $bcolor = $this->_validateColor($p['bcolor']);

        if ($title)
            $title = strip_tags($title);

        if (strtolower($enabled) == 'on') {
            $enabled = true;
        } else {
            $enabled = false;
        }

        if (strtolower($mobile) == 'on') {
            $mobile = true;
        } else {
            $mobile = false;
        }

        if ($position)
            $position = strtolower($position);

        if ($amount !== false) {
            $amount = (int) $amount;
        }

        if ($show !== false) {
            $show = (int) $show;
        }

        if ($pages !== false) {
            $pages = self::explodeCommaIntStringToArrayAndPackAgain($pages);
        }

        if ($posts !== false) {
            $posts = self::explodeCommaIntStringToArrayAndPackAgain($posts);
        }

        if ($type)
            $type = strtolower($type);

        if ($sizeselect)
            $sizeselect = (int) $sizeselect;

        foreach (self::$options as $a => $b) {
            $this->_updateOption($b, $$b);
        }

        $this->_setHeight();

        return true;
    }

    /**
     * validates comma separated integer strings
     * @param $data
     * @return unknown_type
     */
    public static function explodeCommaIntStringToArrayAndPackAgain($data) {
        if ($data == false)
            return $data;

        $x = explode(',', $data);
        $y = array();
        foreach ($x as $a => $b) {
            $z = abs((int) $b);

            if ($z == 0)
                continue;

            if ($z == $b) {
                $y[$z] = $z;
            }
        }
        if (!count($y))
            return false;

        return implode(',', $y);
    }

    protected function _validateColor($color) {

        $subject = $color;
        $subject = strtolower($subject);
        $pattern = '/[a-z0-9]{3,6}/';
        if (strlen($subject) == 3 || strlen($subject) == 6)
            if (preg_match($pattern, $subject, $matches, PREG_OFFSET_CAPTURE))
                return $subject;
        return false;
    }

    protected function _getPath() {
        try {
            $prefix = get_bloginfo('wpurl');
        } catch (Exception $e) {
            $prefix = "";
        }
        return $prefix . "/wp-content/plugins/price-slider";
    }

    /**
     * get options page
     */
    protected function _options() {

        $this->admin = true;

        $tcolor = $this->tcolor;
        $bcolor = $this->bcolor;
        $content = $this->content;
        $title = $this->title;
        $enabled = ($this->enabled) ? 'checked="checked"' : '';
        $position = $this->position;
        $type = $this->type;
        $amount = $this->amount;
        $mobile = ($this->mobile) ? 'checked="checked"' : '';

        $show = $this->show;
        $posts = $this->posts;
        $pages = $this->pages;


        $path = $this->_getPath();
        $fval = _c('Save Changes');


        $this->getJQuery();

        echo <<<EOT
    <link rel="stylesheet" type="text/css" href="{$path}/css/colorpicker.css" />
    <link rel="stylesheet" type="text/css" href="{$path}/css/main.css" />
    <script src="{$path}/js/colorpicker.js" type="text/javascript"></script>

    <div class="b_container">
    <form method="post" action="options-general.php?page=price_slider&updated=true">

    <div class="b_tabs">

        <div class="b_tab">
            <input type="submit" class="button-primary" name="Submit" value="{$fval}" />
        </div>


        <div class="b_tab">
            <input type="submit" class="button-primary" onclick="fToggle(); return false;" value="Slide-in / slide-out test button" />
        </div>
    </div>

    <br />

    <div class="b_tabs">

        <div class="b_tab">

                <h2>Options</h2>

                <label for="title">Title:</label><br />
                <input id="ftitle" type="text" name="title" value="{$title}" class="finput" ><br />
                <br />
                <label for="content">Content:</label><br />
                <textarea id="fcontent" name="content" rows=12 class="finput">{$content}</textarea><br />
                <br/>
                <label for="enabled">Plugin enabled?:</label><br />
                <input type="checkbox" name="enabled" id="fenabled" {$enabled} /><br />
                <br />
                <label for="mobile">Show on mobiles?:</label><br />
                <input type="checkbox" name="mobile" id="fmobile" {$mobile} /><br />
                <br />

EOT;

        /**
         * POSITION
         */
        echo <<<EOT

                <label for="position">Position:</label><br />
                <select name="position" id="fposition" class="finput">

EOT;

        /**
         * @deprecated removed center alignment - it collides with slide-left right system
         */
        foreach (array('left', /* 'center', */ 'right') as $a => $b) {
            echo '<option value="' . strtolower($b) . '"';
            if (strtolower($position) == strtolower($b)) {
                echo ' selected="selected" ';
            }
            echo '>' . strtolower($b) . '</option>';
        }


        echo <<<EOT
                </select>
                <br />
EOT;

        /**
         * SLIDE IN TYPE
         */
        echo <<<EOT

                <br />
                <label for="type">Type:</label><br />
                <select name="type" id="ftype" class="finput">

EOT;

        $types = array(
            array("name" => "slide-in from right / left side", "value" => "side"),
            array("name" => "slide-in from bottom", "value" => "bottom"),
            array("name" => "mouse over", "value" => "mouse")
        );

        foreach ($types as $a => $b) {
            echo '<option value="' . strtolower($b["value"]) . '"';
            if (strtolower($type) == strtolower($b["value"])) {
                echo ' selected="selected" ';
            }
            echo '>' . ucfirst($b["name"]) . '</option>';
        }


        echo <<<EOT
                </select>
                <br />
EOT;

        /**
         * SIZE SELECT
         */
        echo <<<EOT

                <br />
                <label for="sizeselect">Size (changed afted saving):</label><br />
                <select name="sizeselect" id="ftype" class="finput">

EOT;

        $sizeselect = $this->sizeselect;
        $types = array(
            array("name" => "Big", "value" => "15"),
            array("name" => "Medium", "value" => "10"),
            array("name" => "Small", "value" => "5")
        );

        foreach ($types as $a => $b) {
            echo '<option value="' . strtolower($b["value"]) . '"';
            if (strtolower($sizeselect) == strtolower($b["value"])) {
                echo ' selected="selected" ';
            }
            echo '>' . ucfirst($b["name"]) . '</option>';
        }


        echo <<<EOT
                </select>
                <br />
EOT;

        /**
         * ACTIVATION
         */
        echo <<<EOT

                <br />
                <div class="fhidden" name="famounts" id="famounts">
                    <label for="amount">Amount of page scrolled to activate Slider:</label><br />
                    <select name="amount" id="famount" class="finput">

EOT;

        $amounts = array(
            array("name" => "none - always activated", "value" => "0"),
            array("name" => "1/4", "value" => "25"),
            array("name" => "middle", "value" => "50"),
            array("name" => "3/4", "value" => "75"),
            array("name" => "end", "value" => "90")
        );

        foreach ($amounts as $a => $b) {
            echo '<option value="' . strtolower($b["value"]) . '"';
            if (strtolower($amount) == strtolower($b["value"])) {
                echo ' selected="selected" ';
            }
            echo '>' . strtolower($b["name"]) . '</option>';
        }


        echo <<<EOT
                </select>
                <br />

              </div>
EOT;

        /**
         * SHOW ON WHAT?
         */
        echo <<<EOT

                <br />
                <label for="show">Pages to show Slider:</label><br />
                <select name="show" id="fshow" class="finput">

EOT;

        $shows = array(
            array("name" => "show everywhere", "value" => "1"),
            array("name" => "don't show on homepage", "value" => "2"),
            array("name" => "only show on pages", "value" => "3"),
            array("name" => "only show on posts", "value" => "4"),
            array("name" => "show only on certain pages and posts", "value" => "5")
        );

        foreach ($shows as $a => $b) {
            echo '<option value="' . strtolower($b["value"]) . '"';
            if (strtolower($show) == strtolower($b["value"])) {
                echo ' selected="selected" ';
            }
            echo '>' . ucfirst($b["name"]) . '</option>';
        }


        echo <<<EOT
                </select>

                <div class="fhidden" name="fshows" id="fshows">
                    <br />
                    <label for="pages">IDs of pages to show separated by comma:</label><br />
                    <input id="fpages" type="text" name="pages" value="{$pages}" class="finput" ><br />
                    <br />
                    <label for="posts">IDs of posts to show separated by comma:</label><br />
                    <input id="fposts" type="text" name="posts" value="{$posts}" class="finput" >
                </div>

EOT;


        echo <<<EOT
                <input type="hidden" name="tcolor" id="ftcolor" value="{$tcolor}" />
                <input type="hidden" name="bcolor" id="fbcolor" value="{$bcolor}" />
        </div>

        <div class="b_tab">
            <h2>Background color</h2>
            <div style="background: #{$bcolor};" class="c_colorpicker" id="c_cp1">
            </div>
        </div>

        <div class="b_tab">
            <h2>Font color</h2>
            <div style="background: #{$tcolor};" class="c_colorpicker" id="c_cp2">
            </div>
        </div>

    </div>

    </form>

    </div>

EOT;
        ?>

        <script type="text/javascript">
            <!--
            $(document).ready( function() {
                var fposition = '<?php echo $position ?>';
                var ftype = '<?php echo $type ?>';
                var fclass = 'boxx997_' + ftype + '_' + fposition;

                function fUpdateClass() {
                    var classx = 'boxx997_' + ftype + "_" + fposition;
                    if (classx != fclass) {
                        fSlideOut();
                        $("#boxx997").attr('style', ' ');
                        fclass = classx;
                        $("#boxx997").attr( "class", classx );
                        fSlideIn();
                    }
                }

                function fUpdateAmount() {
                    var ftypex = $("#ftype").val();

                    fMouseSlideListener();
                    if (ftypex == "mouse") {
                        $("#famounts").hide();
                    } else {
                        $("#famounts").show();
                    }


                }

                function fMouseSlideListener() {

                    var ftypex = $("#ftype").val();

                    if (ftypex == 'mouse') {
                        $("#boxx997").bind('mouseenter',
                        function() {
                            fSlideIn();
                        }).bind('mouseleave',
                        function() {
                            fSlideOut();
                        });
                    } else {
                        $("#boxx997").unbind();
                    }
                }

                $('#fposition').change( function() {
                    fSlideOut();
                    var curx = $(this).val();
                    position = fposition = curx;
                    fUpdateClass();
                    fSlideIn();
                    return true;
                }
            );

                $('#ftype').change( function() {
                    fSlideOut();
                    var typex = $(this).val();
                    fUpdateAmount();

                    type = ftype = typex;
                    type = typex;
                    fUpdateClass();
                    fSlideIn();
                    return true;
                }
            );




                function _lbx() { $("#labelx997").text( $("#ftitle").val() ) };
                function _cbx() { $("#contentsx997").html( $("#fcontent").val() ) }
                function _fbx() {
                    if ($("#fshow").val() == 5) {
                        $("#fshows").show();
                    } else {
                        $("#fshows").hide();
                    }

                }
                $("#ftitle").change( function() { _lbx() } ).bind('mouseleave', function() { _lbx() } );
                $("#fcontent").change( function() { _cbx() } ).bind('mouseleave', function() { _cbx() } );
                $("#fshow").change( function() { _fbx() } ).bind('mouseleave', function() { _fbx() } );

                _fbx();

                fUpdateClass();
                fUpdateAmount();


            }
        );
            //-->
        </script>

        <script type="text/javascript">

            <!--

            var w1 = false;
            var w2 = false;

            var c1 = '<?php echo $bcolor ?>';
            var c2 = '<?php echo $tcolor ?>';

            $(document).ready(
            function () {
                $('#c_cp1').ColorPicker({
                    flat: false,
                    color: '#' + c1,
                    onShow: function (colpkr) {
                        $(colpkr).fadeIn(500);
                        return false;
                    },
                    onHide: function (colpkr) {
                        $(colpkr).fadeOut(500);
                        return false;
                    },
                    onChange: function (hsb, hex, rgb) {
                        var cx = 'background-color';
                        var cc = 'color';
                        w1 = hex;
                        var t1 = w1;
                        var t2 = w2;
                        $('#container997').attr( 'style', cx + ': #' + t1 + ' !important; '+ cc + ': #' + t2 + ';' );

                        $('#c_cp1').attr('style', "background: #" + hex + " !important; ");
                        $('#fbcolor').val( hex );
                    }});

                $('#c_cp2').ColorPicker({
                    flat: false,
                    color: '#' + c2,
                    onShow: function (colpkr) {
                        $(colpkr).fadeIn(500);
                        return false;
                    },
                    onHide: function (colpkr) {
                        $(colpkr).fadeOut(500);
                        return false;
                    },
                    onChange: function (hsb, hex, rgb) {
                        var cc = 'background-color';
                        var cx = 'color';
                        w2 = hex;

                        var t1 = w1;
                        var t2 = w2;
                        $('#container997').attr( 'style', cx + ': #' + t2 + ' !important; '+ cc + ': #' + t1 + ';' );
                        $('#c_cp2').attr('style', "background: #" + hex + " !important; ");
                        $('#ftcolor').val( hex );
                    }});

                if (type !== 'mouse')
                    fSlideIn();
            }
        );



            //-->
        </script>
        <?php
        $this->slider();
    }

    public function getJQuery() {

        if ($this->jQuerySent)
            return true;

        $path = $this->_getPath();

        echo <<<EOT
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js" type="text/javascript"></script>
<script src="{$path}/js/cookie.js" type="text/javascript"></script>
EOT;

        $this->jQuerySent = true;
        return true;
    }

    /**
     * get slider
     */
    public function slider() {

        if ($this->sliderSend)
            return true;
        $this->sliderSend = true;
        $path = $this->_getPath();

        $this->getJQuery();
        $tcolor = $this->tcolor;
        $bcolor = $this->bcolor;
        $content = $this->content;
        $title = $this->title;
        $amount = $this->amount;


        $enabled = ($this->enabled) ? 'checked="checked"' : '';

        $position = $this->position;
        $type = $this->type;


        $width = $this->width;
        $height = $this->height;
        $label_height = $this->labelHeight;

        $height_minus_label = (int) ( $height - $label_height );

        $minus_width = (int) ( (-1) * $width );
        $half_width = (int) ( $width / 2 );

        $class = false;
        $classBase = "boxx997";

        $class = "{$classBase}_{$type}_{$position}";
        $style = '';

        $container_height = $height_minus_label;

        echo <<<EOT

        <style type='text/css'>

            #boxx997 {
                position: fixed;
                display: block;
                width: {$width}px;
                z-index: 999;
                height: {$height}px;
                text-align: center;
                margin: 0;
            }

            .boxx997_side_right {
                bottom: 0px;
                right: -{$width}px;
            }

            .boxx997_side_left {
                bottom: 0px;
                left: -{$width}px;
            }

            .boxx997_bottom_left {
               bottom: -{$theight}px;
               left: 0;
               right: auto !important;
            }

            .boxx997_bottom_right {
               bottom: -{$theight}px;
               right: 0;
               left: auto !important;
            }

            .boxx997_mouse_left {
               bottom: -{$height_minus_label}px;
               left: 0;
               right: auto !important;
            }

            .boxx997_mouse_right {
               bottom: -{$height_minus_label}px;
               right: 0;
               left: auto !important;
            }


            #labelx997 {
                height: {$label_height}px;
                margin: 0 5px 0 5px;
            }

            #container997 {
                display: block;
                font-size: 12px !important;
                background-color: #{$bcolor};
                color: #{$tcolor};
                width: {$width}px;
                height: {$height}px;
            }

            #contentsx997 {
                margin: 0 5px 0 5px;
                height: {$container_height}px !important;
                overflow: hidden !important;
            }

            .fmclose {
                display: block;
                float: right;
                width: 10px;
                height: 10px;
                margin: 0 10px 0 5px;
                cursor: pointer;
            }

            .b_fright {
                float: right;
            }
            .b_fright a, .b_fright a:visited {
                text-decoration: none;
                color: inherit !important;
            }

        </style>

        <div class="{$class}" id="boxx997">
                <div id="container997">
                    <div id="labelx997">
                            {$title}<div class="fmclose" id="fmclose">[x]</div>
                    </div>
                    <div id="contentsx997">
                       {$content}<div class="b_fright"><a target="_blank" title="About the Price Slider" href="http://www.medicalpriceonline.com/#priceslider">[?]</a></div>
                    </div>
        </div>

EOT;
        ?>
        <script type="text/javascript">
            <!--
            var position = '<?php echo $position ?>';
            var type = '<?php echo $type ?>';
            var visible = false;
            var width = <?php echo (int) $width ?>;
            var height = <?php echo (int) $height; ?>;
            var time = <?php echo (int) $this->slideMsecTime ?>;

            function fSleep(milliseconds) {
                var start = new Date().getTime();
                for (var i = 0; i < 17; i++) {
                    if ((new Date().getTime() - start) > milliseconds){
                        break;
                    }
                }
            }

            function fToggle() {
                if (visible) {
                    fSlideOut();
                } else {
                    fSlideIn();
                }
                return true;
            }

            function fSlideIn() {
                visible = true;
                if (type == "bottom") {
                    fBottomSlideIn();
                    return true;
                }

                if (type == "mouse") {
                    fMouseSlideIn();
                    return true;
                }

                if (position == 'right') {
                    fRightSlideIn();
                    return true;
                } else {
                    fLeftSlideIn();
                    return true;
                }
            }

            function fSlideOut() {
                visible = false;
                if (type == "bottom") {
                    fBottomSlideOut();
                    return true;
                }

                if (type == "mouse") {
                    fMouseSlideOut();
                    return true;
                }

                if (position == 'left') {
                    fLeftSlideOut();
                    return true;
                } else {
                    fRightSlideOut();
                    return true;
                }
            }

            function fMouseSlideIn() {
                $('#boxx997').stop().
                    animate({ bottom: 0 }, time);
            }

            function fMouseSlideOut() {
                $('#boxx997').stop().
                    animate({ bottom: -<?php echo (int) abs($height_minus_label) ?> }, time);
            }


            function fBottomSlideIn() {
                $('#boxx997').stop().
                    animate({ bottom: 0 }, time);
            }

            function fBottomSlideOut() {
                $('#boxx997').stop().
                    animate({ bottom: -height }, time);
            }

            function fRightSlideIn() {
                $('#boxx997').stop().
                    animate({ right: 0 }, time);
            }

            function fRightSlideOut() {
                $('#boxx997').stop().
                    animate({ right: -width }, time);
            }

            function fLeftSlideIn() {
                $('#boxx997').stop().
                    animate({ left: 0 }, time, function() { $("#boxx997").attr('style', 'left: 0px') } );
            }

            function fLeftSlideOut() {
                $('#boxx997').stop().
                    animate({ left: "-<?php echo $width ?>" }, time, function() { $("#boxx997").attr('style', 'left: -<?php echo (int) $width ?>px') } );
            }
            $(document).ready( function () {

                var fDate = new Date();

                $('#fmclose').click( function() {
                    fSlideOut();

                    $.cookie('slider_cookie_close', true, {
                        expires: new Date( ( fDate.getTime() + 30*60*1000 )  ),
                        path: "/"

                    });
                }
            );

            } ) ;
            //-->
        </script>

        <?php
    }

    public function sliderOnScroll() {

        $amount = $this->amount;
        ?>
        <script type="text/javascript">
            <!--
            $(document).ready(
            function() {

                var fKey = 'slider_cookie_close';
                var fVisible = false;


                function fSliderViewport() {

                    if (!$.cookie( fKey) ) {

                        var fScroll = $(window).scrollTop();
                        var fViewportHeight = $(window).height();
                        var fDocumentHeight = $(document).height();
                        var fAmount = <?php echo (int) $amount ?>;

                        if ( (fScroll + fViewportHeight) >= ( (fDocumentHeight) * ( fAmount / 100 )  )  ) {
                            if (!fVisible) {
                                fSlideIn();
                                fVisible = true;
                                return true;
                            }
                        } else {
                            if (fVisible) {
                                fSlideOut();
                                fVisible = false;
                                return true;
                            }

                        }
                    }


                }

                $(document).scroll( function() {
                    fSliderViewport();
                }



            );
                $(window).resize( function() {
                    fSliderViewport();
                });

                fSliderViewport();

            }
        );
            //-->
        </script>
        <?php
    }

    public function sliderOnMouse() {
        ?>
        <script type="text/javascript">
            <!--
            $(document).ready(
            function() {

                $("#boxx997").bind('mouseenter',
                function() {
                    fSlideIn();
                }).bind('mouseleave',
                function() {
                    fSlideOut();
                });

            }
        )
            //-->
        </script>
        <?php
    }

    public function isEnabled() {
        return $this->enabled;
    }

    public function getShowStatus() {
        return $this->show;
    }

    public function isPageInPages($id) {
        $id = (int) $id;
        $ar = explode($delimiter = ',', $string = $this->pages);
        if (in_array((int) $id, $ar, false))
            return true;
        return false;
    }

    public function isPostInPosts($id) {
        $id = (int) $id;
        $ar = explode($delimiter = ',', $string = $this->posts);
        if (in_array((int) $id, $ar, false))
            return true;
        return false;
    }

    public function getType() {
        return $this->type;
    }

    public function getMobileStatus() {
        return $this->mobile;
    }

}
?>
