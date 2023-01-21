<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * PHP Excel library. Helper class to make spreadsheet creation easier.
 *
 * @package    Spreadsheet
 * @author     Flynsarmy, Dmitry Shovchko
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 */
class Spreadsheet
{
  private $response;
	private $_spreadsheet;
	private $exts = array(
		'CSV'		    => 'csv',
		'PDF'		    => 'pdf',
		'Excel5' 	  => 'xls',
		'Excel2007' => 'xlsx',
	);
	private $mimes = array(
        'CSV' 		  => 'text/csv',
        'PDF' 		  => 'application/pdf',
        'Excel5' 	  => 'application/vnd.ms-excel',
        'Excel2007' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    );

	/**
	 * Creates the spreadsheet with given or default settings
	 *
	 * @param array $headers with optional parameters: title, subject, description, author
	 * @return void
	 */
//------------------------------------------------------------------------------
	public function __construct($headers=array())
	{
		$headers = array_merge(array(
			'title'			  => 'New Spreadsheet',
			'subject'		  => 'New Spreadsheet',
			'description'	=> 'New Spreadsheet',
			'author'		  => 'ClubSuntory',
		), $headers);

		$this->_spreadsheet = new PHPExcel();

		// Set properties
		$this->_spreadsheet->getProperties()
			->setCreator( $headers['author'] )
			->setTitle( $headers['title'] )
			->setSubject( $headers['subject'] )
			->setDescription( $headers['description'] );
    $this->_spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
    $this->_spreadsheet->getDefaultStyle()->getFont()->setSize(11);
    $this->_spreadsheet->getDefaultStyle()->getAlignment()->setWrapText(true);
    $this->_spreadsheet->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $this->_spreadsheet->getActiveSheet()->getPageMargins()->setTop(0.4);
    $this->_spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.4);
    $this->_spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.4);
    $this->_spreadsheet->getActiveSheet()->getPageMargins()->setBottom(0.4);

//    $this->_spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setAutoSize(true);
	}
//------------------------------------------------------------------------------
//грузим шаблон для отчётов
  public function load_pattern($arg_filename)
  {
    if (file_exists($arg_filename))
    {
      $objReader = new PHPExcel_Reader_Excel5();
      $this->_spreadsheet = $objReader->load($arg_filename);
//      print_r($this->_spreadsheet);
//      exit;
    }
    else
    {
      throw new Exception("Файла ".$arg_filename." не существует!");
//    	echo('файл не существует!');
//    	exit;
    }
  }
//------------------------------------------------------------------------------
//добавляем в таблицу один ряд
  public function add_row($arg_row_number, $arg_row_count = 1)
  {
    $activeSheet = $this->_spreadsheet->getActiveSheet();
    if (isset($this->_spreadsheet))
    {
      $activeSheet->insertNewRowBefore($arg_row_number, $arg_row_count);
    }
    else
    {
      throw new Exception("Ошибка при добавлении строк!");
//    	echo('апшипка!');
//    	exit;
    }
  }
//------------------------------------------------------------------------------
//записываем массив построчно
  public function write_row($arg_x, $arg_y, $arg_array)
  {
    $column_index = $arg_x;
    $row_index = $arg_y;
    $activeSheet = $this->_spreadsheet->getActiveSheet();
    foreach($arg_array as $row_key=>$row_value)
    {
      if (is_array($row_value))//массив двумерный
      {
        foreach($row_value as $column_key=>$column_value)
        {
          $activeSheet->setCellValueByColumnAndRow($column_index++, $row_index++, $column_value);
        }
      }
      else
      {
        $activeSheet->setCellValueByColumnAndRow($column_index++, $row_index++, $row_value);
      }
    }

  }
//------------------------------------------------------------------------------
//записываем массив по столбцам (надо-нет?)
  public function write_column()
  {

  }
//------------------------------------------------------------------------------
//отсылаем отчёт браузеру
	public function send($settings=array())
	{
		$settings = array_merge(array(
			'format'		=> 'Excel2007',
			'name'			=> 'NewSpreadsheet',
		), $settings);

		$writer = PHPExcel_IOFactory::createWriter($this->_spreadsheet, $settings['format']);

		$ext = $this->exts[$settings['format']];
		$mime = $this->mimes[$settings['format']];
		
		$this->_spreadsheet->removeSheetByIndex(0);

//		$request = Request::initial();
//		$request->headers['Content-Type'] = $mime;
//		$request->headers['Content-Disposition'] = 'attachment;filename="'.$settings['name'].'.'.$ext.'"';
//		$request->headers['Cache-Control'] = 'max-age=0';
//    $response = Response::factory();
    $this->response->headers(array());
		$this->response->headers(array('content-type' => $mime,
	                           'content-disposition' => 'attachment; filename='.$settings['name'].'.'.$ext.';',
	                           'cache-control' => 'max-age=0'));
//    print_r($this->response->headers()); exit;
    $this->response->send_headers();
		if ($settings['format'] == 'CSV')
		{
			$writer->setUseBOM(true);
		}

		$writer->save('php://output');
		exit;
	}
//------------------------------------------------------------------------------
	public function set_report_data(array $data, $sheet_name="", array $column_width = NULL)
//	public function set_report_data(array $data)
	{
    if (!(array_key_exists('column_titles',$data) AND
         array_key_exists('data',$data) AND
         array_key_exists('caption',$data) AND
         array_key_exists('reportdate',$data) AND
         array_key_exists('footer',$data)))
    {      return;
    }
    //объект для форматированного текста в ячейках
//    $objRichText = new PHPExcel_RichText();
//    $objRichText->createText('This invoice is ');
    $borderStyleArray = array(
      'borders' => array(
		    'outline' => array(
		      'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
			    'color' => array('argb' => 'FF000000'),),
        'inside' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN,
			    'color' => array('argb' => 'FF000000'),),
    	),);
		$styleArray = array(
			'font' => array(
				'bold' => true,
				'color' =>array('argb' => 'FF000000',),
        'size' => 11,
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			),
		);
		$headers_styleArray = array(
			'font' => array(
				'bold' => true,
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			),
		);

    if ($sheet_name!="")
    {    	$sheet = $this->_spreadsheet->createSheet();
      $sheet->setTitle($sheet_name);
    }
    else
    {    	$sheet = $this->_spreadsheet->getActiveSheet();
    }
//    $sheet = $this->_spreadsheet->getActiveSheet();
    //объединяем ячейки для заголовка отчёта
    $column_amount = count($data['column_titles'])-1;
    $rows_amount = count($data['data'])+4;
//    print_r($column_amount."-".$rows_amount);
//    exit;
    $sheet->mergeCellsByColumnAndRow(0, 1, $column_amount, 1);
    $sheet->mergeCellsByColumnAndRow(0, 2, $column_amount, 2);
    //форматируем ячейки
    $sheet->getStyle('A1')->applyFromArray($styleArray);
    $sheet->getStyle('A2')->applyFromArray($styleArray);
	//$sheet->getStyle('A3')->applyFromArray($headers_styleArray);
    $sheet->setCellValueByColumnAndRow(0, 1, strip_tags($data['caption']));
    $sheet->setCellValueByColumnAndRow(0, 2, strip_tags($data['reportdate']));
//    print_r(PHPExcel_Cell::stringFromColumnIndex(0).
//    '1:'.PHPExcel_Cell::stringFromColumnIndex($column_amount).$rows_amount);
//    exit;
    $sheet->getStyle(PHPExcel_Cell::stringFromColumnIndex(0).
    '1:'.PHPExcel_Cell::stringFromColumnIndex($column_amount).$rows_amount)->applyFromArray($borderStyleArray);
		foreach ($data['column_titles'] as $column=>$value)
		{
			$sheet->setCellValueByColumnAndRow($arg_offset['x']+$column_num++, 3, strip_tags($value));
	  }
    $this->set_sheet_data($data['data'], $sheet, array('x'=>'0', 'y'=>'4'));
    //объединяем ячейки для футера отчёта
    $sheet->mergeCellsByColumnAndRow(1, $rows_amount, $column_amount, $rows_amount);
    $sheet->setCellValueByColumnAndRow(1, $rows_amount, strip_tags($data['footer']));
    if (isset($column_width))
    {
      foreach($column_width as $key => $value)
        if ($value != 0)
          $sheet->getColumnDimensionByColumn($key)->setWidth($value);
        else
          $sheet->getColumnDimensionByColumn($key)->setAutoSize(true);
    }
	}
//------------------------------------------------------------------------------
//##############################################################################
//------------------------------------------------------------------------------
  public function set_response($arg_response)
  {
    $this->response = $arg_response;
  }
//------------------------------------------------------------------------------
	public function set_active_sheet($index)
	{
		$this->_spreadsheet->setActiveSheetIndex($index);
	}

	/**
	 * Get the currently active sheet
	 *
	 * @return PHPExcel_Worksheet
	 */
//------------------------------------------------------------------------------
	public function get_active_sheet()
	{
		return $this->_spreadsheet->getActiveSheet();
	}

	/**
	 * Writes cells to the spreadsheet
	 *  array(
	 *	   1 => array('A1', 'B1', 'C1', 'D1', 'E1'),
	 *	   2 => array('A2', 'B2', 'C2', 'D2', 'E2'),
	 *	   3 => array('A3', 'B3', 'C3', 'D3', 'E3'),
	 *  );
	 *
	 * @param array of array( [row] => array([col]=>[value]) ) ie $arr[row][col] => value
	 * @return void
	 */
//------------------------------------------------------------------------------
	public function set_data(array $data, $multi_sheet=false)
	{
		//Single sheet ones can just dump everything to the current sheet
		if ( !$multi_sheet )
		{
			$sheet = $this->_spreadsheet->getActiveSheet();
			$this->set_sheet_data($data, $sheet);
		}
		//Have to do a little more work with multi-sheet
		else
		{
			foreach ($data as $sheetname=>$sheetData)
			{
				$sheet = $this->_spreadsheet->createSheet();
				$sheet->setTitle($sheetname);
				$this->set_sheet_data($sheetData, $sheet, array('x'=>'0','y'=>'5'));
			}
			//Now remove the auto-created blank sheet at start of XLS
			$this->_spreadsheet->removeSheetByIndex(0);
		}
	}
//------------------------------------------------------------------------------
	protected function set_sheet_data(array $data, PHPExcel_Worksheet $sheet, $arg_offset=array('x'=>'0','y'=>'0'))
	{
//		foreach ($data as $row =>$columns)
//			foreach ($columns as $column=>$value)
//				$sheet->setCellValueByColumnAndRow($column, $row, $value);
		foreach ($data as $row =>$columns)
		{
		  $column_num = 0;
			foreach ($columns as $column=>$value)
			{
				$sheet->setCellValueByColumnAndRow($arg_offset['x']+$column_num++, $arg_offset['y']+$row, $value);
		  }
		}
	}

	/**
	 * Writes spreadsheet to file
	 *
	 * @param array $settings with optional parameters: format, path, name (no extension)
	 * @return Path to spreadsheet
	 */
//------------------------------------------------------------------------------
	public function save($settings=array())
	{
		$settings = array_merge(array(
			'format'		=> 'Excel2007',
			'path'			=> APPPATH.'assets/downloads/spreadsheets/',
			'name'			=> 'NewSpreadsheet',
		), $settings);

		//Generate full path
		$settings['fullpath'] = $settings['path'].$settings['name'].'_'.time().'.'.$this->exts[$settings['format']];

		$writer = PHPExcel_IOFactory::createWriter($this->_spreadsheet, $settings['format']);

		if ($settings['format'] == 'CSV')
		{
			$writer->setUseBOM(true);
		}
		$writer->save($settings['fullpath']);
		return $settings['fullpath'];
	}

	/**
	 * Send spreadsheet to browser
	 *
	 * @param array $settings with optional parameters: format, name (no extension)
	 * @return void
	 */
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------

}