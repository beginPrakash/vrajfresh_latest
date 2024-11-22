<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CodeIgniter PDF Library
 *
 * Generate PDF in CodeIgniter applications.
 *
 * @package            CodeIgniter
 * @subpackage        Libraries
 * @category        Libraries
 * @author            CodexWorld
 * @license            https://www.codexworld.com/license/
 * @link            https://www.codexworld.com
 */

// reference the Dompdf namespace
use Dompdf\Dompdf;
use Dompdf\Options;

class Pdf
{
    public function __construct(){
        
        // include autoloader
        require_once dirname(__FILE__).'/dompdf/autoload.inc.php';
        
        // instantiate and use the dompdf class
        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        // $options->set('defaultFontSize', 5); // Set font size ratio here
        $options->set('isRemoteEnabled', true);
        $pdf = new DOMPDF($options);
        
        $CI =& get_instance();
        $CI->dompdf = $pdf;
        
    }
}
?>