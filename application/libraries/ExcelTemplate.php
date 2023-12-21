<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

include_once APPPATH . '/third_party/PHPExcel/Classes/PHPExcel.php';

class ExcelTemplate {
    var $PAGE_F4 = array(215,330);
    var $PAGE_A4 = array(210,297);
    var $PATH_FILE = "uploads/temp_excel/";

    function Set_StratExcel(){
        global $DataOption;

        ini_set('memory_limit', '1024M');
        $phpExcel = new PHPExcel();
        $phpExcel->getDefaultStyle()->getFont()->setName('Calibri'); // Setting font to Arial Black
        $phpExcel->getDefaultStyle()->getFont()->setSize(12); // Setting font size to 12
        $phpExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $phpExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        //Setting description, creator and title
        $phpExcel->getProperties()->setTitle("");
        $phpExcel->getProperties()->setCreator("");
        $phpExcel->getProperties()->setDescription("Excel SpreadSheet in PHP");
        $writer = PHPExcel_IOFactory::createWriter($phpExcel, "Excel2007");

        return array("phpExcel"=>$phpExcel, "writer"=>$writer);
    }

}

$ExcelTemplate = new ExcelTemplate();