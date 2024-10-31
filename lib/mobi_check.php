<?php

/**

  Simple Class to detect mobile devices.
  Based on data from http://wurfl.sourceforge.net/

  GNU LESSER GENERAL PUBLIC LICENSE

  Version 3, 29 June 2007

  Copyright © 2007 Free Software Foundation, Inc. <http://fsf.org/>

  Everyone is permitted to copy and distribute verbatim copies of this license document, but changing it is not allowed.

  This version of the GNU Lesser General Public License incorporates the terms and conditions of version 3 of the GNU General Public License, supplemented by the additional permissions listed below.
  0. Additional Definitions.

  As used herein, “this License” refers to version 3 of the GNU Lesser General Public License, and the “GNU GPL” refers to version 3 of the GNU General Public License.

  “The Library” refers to a covered work governed by this License, other than an Application or a Combined Work as defined below.

  An “Application” is any work that makes use of an interface provided by the Library, but which is not otherwise based on the Library. Defining a subclass of a class defined by the Library is deemed a mode of using an interface provided by the Library.

  A “Combined Work” is a work produced by combining or linking an Application with the Library. The particular version of the Library with which the Combined Work was made is also called the “Linked Version”.

  The “Minimal Corresponding Source” for a Combined Work means the Corresponding Source for the Combined Work, excluding any source code for portions of the Combined Work that, considered in isolation, are based on the Application, and not on the Linked Version.

  The “Corresponding Application Code” for a Combined Work means the object code and/or source code for the Application, including any data and utility programs needed for reproducing the Combined Work from the Application, but excluding the System Libraries of the Combined Work.
  1. Exception to Section 3 of the GNU GPL.

  You may convey a covered work under sections 3 and 4 of this License without being bound by section 3 of the GNU GPL.
  2. Conveying Modified Versions.

  If you modify a copy of the Library, and, in your modifications, a facility refers to a function or data to be supplied by an Application that uses the facility (other than as an argument passed when the facility is invoked), then you may convey a copy of the modified version:

  a) under this License, provided that you make a good faith effort to ensure that, in the event an Application does not supply the function or data, the facility still operates, and performs whatever part of its purpose remains meaningful, or
  b) under the GNU GPL, with none of the additional permissions of this License applicable to that copy.

  3. Object Code Incorporating Material from Library Header Files.

  The object code form of an Application may incorporate material from a header file that is part of the Library. You may convey such object code under terms of your choice, provided that, if the incorporated material is not limited to numerical parameters, data structure layouts and accessors, or small macros, inline functions and templates (ten or fewer lines in length), you do both of the following:

  a) Give prominent notice with each copy of the object code that the Library is used in it and that the Library and its use are covered by this License.
  b) Accompany the object code with a copy of the GNU GPL and this license document.

  4. Combined Works.

  You may convey a Combined Work under terms of your choice that, taken together, effectively do not restrict modification of the portions of the Library contained in the Combined Work and reverse engineering for debugging such modifications, if you also do each of the following:

  a) Give prominent notice with each copy of the Combined Work that the Library is used in it and that the Library and its use are covered by this License.
  b) Accompany the Combined Work with a copy of the GNU GPL and this license document.
  c) For a Combined Work that displays copyright notices during execution, include the copyright notice for the Library among these notices, as well as a reference directing the user to the copies of the GNU GPL and this license document.
  d) Do one of the following:
  0) Convey the Minimal Corresponding Source under the terms of this License, and the Corresponding Application Code in a form suitable for, and under terms that permit, the user to recombine or relink the Application with a modified version of the Linked Version to produce a modified Combined Work, in the manner specified by section 6 of the GNU GPL for conveying Corresponding Source.
  1) Use a suitable shared library mechanism for linking with the Library. A suitable mechanism is one that (a) uses at run time a copy of the Library already present on the user's computer system, and (b) will operate properly with a modified version of the Library that is interface-compatible with the Linked Version.
  e) Provide Installation Information, but only if you would otherwise be required to provide such information under section 6 of the GNU GPL, and only to the extent that such information is necessary to install and execute a modified version of the Combined Work produced by recombining or relinking the Application with a modified version of the Linked Version. (If you use option 4d0, the Installation Information must accompany the Minimal Corresponding Source and Corresponding Application Code. If you use option 4d1, you must provide the Installation Information in the manner specified by section 6 of the GNU GPL for conveying Corresponding Source.)

  5. Combined Libraries.

  You may place library facilities that are a work based on the Library side by side in a single library together with other library facilities that are not Applications and are not covered by this License, and convey such a combined library under terms of your choice, if you do both of the following:

  a) Accompany the combined library with a copy of the same work based on the Library, uncombined with any other library facilities, conveyed under the terms of this License.
  b) Give prominent notice with the combined library that part of it is a work based on the Library, and explaining where to find the accompanying uncombined form of the same work.

  6. Revised Versions of the GNU Lesser General Public License.

  The Free Software Foundation may publish revised and/or new versions of the GNU Lesser General Public License from time to time. Such new versions will be similar in spirit to the present version, but may differ in detail to address new problems or concerns.

  Each version is given a distinguishing version number. If the Library as you received it specifies that a certain numbered version of the GNU Lesser General Public License “or any later version” applies to it, you have the option of following the terms and conditions either of that published version or of any later version published by the Free Software Foundation. If the Library as you received it does not specify a version number of the GNU Lesser General Public License, you may choose any version of the GNU Lesser General Public License ever published by the Free Software Foundation.

  If the Library as you received it specifies that a proxy can decide whether future versions of the GNU Lesser General Public License shall apply, that proxy's public statement of acceptance of any version is permanent authorization for you to choose that version for the Library.

 */
class MobiCheck {

    private static $instance;

    /**
     * Regexp strings for various mobiles
     * @var type
     */
    private static $options;
    private static $ua;

    protected function __construct() {
        self::$options = array(
            "android" => "/android/i",
            "opera" => "/opera (mini|mobi)/i",
            "touchpads" => "/(touchpad|webOS|hpwOS|wOSBrowser)/i",
            "apple ipod" => "/ipod/i",
            "apple ipad" => "/ipad/i",
            "blackberry" => "/blackberry/i",
            "palm" => "/(pre\/|hiptop|palm os|palm|avantgo|plucker|xiino|blazer|elaine)/i",
            "windows" => "/(iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile)/i",
            "other mobile" => "/(mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|s800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|d736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |sonyericsson|samsung|240x|x320|vx10|nokia|sony cmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|psp|treo)/i",
            "other mobile start string" => "/^(1207|3gso|4thp|501i|502i|503i|504i|505i|506i|6310|6590|770s|802s|a wa|acer|acs\-|airn|alav|asus|attw|au\-m|aur |aus |abac|acoo|aiko|alco|alca|amoi|anex|anny|anyw|aptu|arch|argo|bell|bird|bw\-n|bw\-u|beck|benq|bilb|blac|c55/|cdm\-|chtm|capi|cond|craw|dall|dbte|dc\-s|dica|ds\-d|ds12|dait|devi|dmob|doco|dopo|el49|erk0|esl8|ez40|ez60|ez70|ezos|ezze|elai|emul|eric|ezwa|fake|fly\-|fly_|g\-mo|g1 u|g560|gf\-5|grun|gene|go.w|good|grad|hcit|hd\-m|hd\-p|hd\-t|hei\-|hp i|hpip|hs\-c|htc |htc\-|htca|htcg|htcp|htcs|htct|htc_|haie|hita|huaw|hutc|i\-20|i\-go|i\-ma|i230|iac|iac\-|iac/|ig01|im1k|inno|iris|jata|java|kddi|kgt|kgt/|kpt |kwc\-|klon|lexi|lg g|lg\-a|lg\-b|lg\-c|lg\-d|lg\-f|lg\-g|lg\-k|lg\-l|lg\-m|lg\-o|lg\-p|lg\-s|lg\-t|lg\-u|lg\-w|lg/k|lg/l|lg/u|lg50|lg54|lge\-|lge/|lynx|leno|m1\-w|m3ga|m50/|maui|mc01|mc21|mcca|medi|meri|mio8|mioa|mo01|mo02|mode|modo|mot |mot\-|mt50|mtp1|mtv |mate|maxo|merc|mits|mobi|motv|mozz|n100|n101|n102|n202|n203|n300|n302|n500|n502|n505|n700|n701|n710|nec\-|nem\-|newg|neon|netf|noki|nzph|o2 x|o2\-x|opwv|owg1|opti|oran|p800|pand|pg\-1|pg\-2|pg\-3|pg\-6|pg\-8|pg\-c|pg13|phil|pn\-2|pt\-g|palm|pana|pire|pock|pose|psio|qa\-a|qc\-2|qc\-3|qc\-5|qc\-7|qc07|qc12|qc21|qc32|qc60|qci\-|qwap|qtek|r380|r600|raks|rim9|rove|s55/|sage|sams|sc01|sch\-|scp\-|sdk/|se47|sec\-|sec0|sec1|semc|sgh\-|shar|sie\-|sk\-0|sl45|slid|smb3|smt5|sp01|sph\-|spv |spv\-|sy01|samm|sany|sava|scoo|send|siem|smar|smit|soft|sony|t\-mo|t218|t250|t600|t610|t618|tcl\-|tdg\-|telm|tim\-|ts70|tsm\-|tsm3|tsm5|tx\-9|tagt|talk|teli|topl|hiba|up.b|upg1|utst|v400|v750|veri|vk\-v|vk40|vk50|vk52|vk53|vm40|vx98|virg|vite|voda|vulc|w3c |w3c\-|wapj|wapp|wapu|wapm|wig |wapi|wapr|wapv|wapy|wapa|waps|wapt|winc|winw|wonu|x700|xda2|xdag|yas\-|your|zte\-|zeto|aste|audi|avan|blaz|brew|brvw|bumb|ccwa|cell|cldc|cmd\-|dang|eml2|fetc|hipt|http|ibro|idea|ikom|ipaq|jbro|jemu|jigs|keji|kyoc|kyok|libw|m\-cr|midp|mmef|moto|mwbp|mywa|newt|nok6|o2im|pant|pdxg|play|pluc|port|prox|rozo|sama|seri|smal|symb|tosh|treo|upsi|vx52|vx53|vx60|vx61|vx70|vx80|vx81|vx83|vx85|wap\-|webc|whit|wmlb|xda\-)/i"
        );

        if (isset($_SERVER['SERVER_PROTOCOL'])) {
            self::$ua = $_SERVER['HTTP_USER_AGENT'];
        } else {
            self::$ua = "could not determine";
        }
    }

    /**
     * Return class instance
     * @return MobiCheck instance
     */
    public function getInstance() {
        if (!self::$instance) {
            self::$instance = new MobiCheck();
        }
        return self::$instance;
    }

    /**
     * check if debice is mobile
     * @return boolean is mobile?
     */
    public function isMobile() {
        foreach (self::$options as $a => $b) {
            if (@preg_match($b, self::$ua)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks for mobile status and redirects for mobile site or regular site
     * @param string $mobileSite
     * @param string $regularSite
     */
    public function redirect($mobileSite, $regularSite) {
        if (isMobile()) {
            httpRedirect($mobileSite);
        } else {
            httpRedirect($regularSite);
        }
    }

    private function httpRedirect($string) {
        header("Location: {$string}");
        die();
    }

}
