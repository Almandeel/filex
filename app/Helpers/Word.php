<?php
namespace App\Hepers;
class Word
{
    public static function read($file_name){
        $file_entries=Fileentry::all();
        $file=public_path('storage\\'.$file_name);
        
        
        //$file = __DIR__ . '\guru.bilagi.m.docx';
        $phpWord = IOFactory::createReader('Word2007')->load($file);
        
        
        
        
        
        if(method_exists($phpWord, 'getSections')) {
            foreach ($phpWord->getSections() as $section) {
                
                $body = '';
                
                if (method_exists($section, 'getElements')) {
                    
                    foreach ($section->getElements() as $element) {
                        if (class_exists(Text::class)) {
                            if (method_exists($element, 'getText')) {
                                
                                if (method_exists($element, 'getFontStyle')) {
                                    
                                    $body .= $element->getText();
                                    
                                }
                                
                                
                                if (class_exists(TextRun::class)) {
                                    
                                    
                                    if (method_exists($element, 'getFontStyle')) {
                                        $font = $element->getFontStyle();
                                        $bold = $font->isBold() ? 'font-weight:700;' : '';
                                        $color = $font->getColor();
                                        $size = $font->getSize() / 10;
                                        $fontFamily = $font->getName();
                                        
                                        $body .= '<span  style="font-size:' . $size . 'em; font-family:' . $fontFamily . '; color:' . $color . ';' . $bold . '">';
                                        $body .= $element->getText() . '</span>';
                                        
                                        
                                    }
                                }
                                
                                
                            }
                            if (class_exists(TextBreak::class)) {
                                $body .= '<br/>';
                            }
                            
                            if (class_exists(Table::class)) {
                                $body .= '<table border="2px">';
                                if (method_exists($element, 'getRows')) {
                                    $row = $element->getRows();
                                    foreach ($row as $rows) {
                                        $body .= '<tr>';
                                        if (method_exists($element, 'getCell')) {
                                            $cells = $rows->getCell();
                                            foreach ($cells as $cell) {
                                                $body .= '<td style="width:' . $cell->getWidth . '">';
                                                $celements = $cell->getElements();
                                                foreach ($celements as $celem) {
                                                    if (class_exists(Text::class) == $celem) {
                                                        $body .= $celem->getText();
                                                    } else if (class_exists(TextRun::class) == $celem) {
                                                        foreach ($celements as $text) {
                                                            $body .= $text->getText();
                                                        }
                                                    }
                                                }
                                                $body .= '</td>';
                                            }
                                        }
                                        
                                        
                                        $body .= '</tr>';
                                        
                                    }
                                }
                            }
                            
                            
                        }
                        
                        
                    }
                }
                
                
                if (class_exists(TextBreak::class)) {
                    $body .= '<br/>';
                }
                
                
                return ['body', 'file_entries'];
            }
        }
    }
    
    public static function writeHtml($html){
        $pw = new \PhpOffice\PhpWord\PhpWord();
        
        /* [THE HTML] */
        $section = $pw->addSection();
        $html = "<h1>HELLO WORLD!</h1>";
        $html .= "<p>This is a paragraph of random text</p>";
        $html .= "<table><tr><td>A table</td><td>Cell</td></tr></table>";
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);
        
        /* [SAVE FILE ON THE SERVER] */
        // $pw->save("html-to-doc.docx", "Word2007");
        
        /* [OR FORCE DOWNLOAD] */
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment;filename="convert.docx"');
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($pw, 'Word2007');
        $objWriter->save('php://output');
    }
    
    public static function store(Request $request)
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $text = $section->addText($request->get('name'));
        $text = $section->addText($request->get('email'));
        $text = $section->addText($request->get('number'),array('name'=>'Arial','size' => 20,'bold' => true));
        $section->addImage("./images/Krunal.jpg");
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('Appdividend.docx');
        return response()->download(public_path('Appdividend.docx'));
    }
}