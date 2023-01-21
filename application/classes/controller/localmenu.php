<?php
defined('SYSPATH') or die('No direct script access.');

class Controller_Localmenu extends Controller_DefaultTemplate
{

	public $template = 'templates/localmenu';
		public function action_index()
		{
		}
//------------------------------------------------------------------------------
		public function action_askucurrents()
		{
			//$styles = array('css/local_menu/style.css'=>'screen');
			$scripts = array('js/asku_currents/functions.js');
			$this->template->scripts = $scripts;
			//$this->template->styles = $styles;
//			$id = $this->request->param('id','default');
			$params = array();
			$tmp_params = explode('&', $this->request->param('id','default'));
			foreach($tmp_params as $key => $value)
			{
				$tmp_param = explode('=', $value);
				$params[$tmp_param[0]]=$tmp_param[1];
			}
			$id = $params['id'];
			$page_name = array('oxygen'=>'Кислород',
                         'steam'=>'Пар',
                         'natural_gas'=>'Природный газ',
                         'koks_gas'=>'Коксовый газ',
                         'heating_water'=>'Теплофикационная вода',
                         'compressed_air'=>'Сжатый воздух',
												 'electro'=>'Электроэнергия',
                         'drinking_water'=>'Пожарно-питьевая вода',
                         'default'=>'Другое');
			$this->template->title = $page_name[$id].'. Текущие данные АСКУ ТЭР';
			$data['menutitle']=$page_name[$id].'. Текущие данные АСКУ ТЭР';
			$menu_view = View::factory('menu/main_menu',$data)->render();
			$this->template->menu = $menu_view;
			$this->template->on_body_load_js = 'SendRequest()';
			$local_menu_view = View::factory('menu/askucurrents_menu')->render();
			$this->template->local_menu = $local_menu_view;
			$content_view = View::factory('asku/currents/'.$id)->render();
			$this->template->content = $content_view;
		}
//------------------------------------------------------------------------------
		public function action_csireports()
		{
			$styles = array('css/calendar/aqua/theme.css'=>'screen');
			$scripts = array('js/calendar/calendar-setup.js', 'js/calendar/lang/calendar-ru.js', 'js/calendar/calendar.js', 'js/calendar/checkDate.js');
			$this->template->styles = $styles;
			$this->template->scripts = $scripts;
			//Названия отчётов
			$title =array
			(
				'mech_working_time'=>'Время работы механизма. ',
				'tech_parameters'=>'Архив технологических параметров. ',
				'chamber_drier1_alarm_messages'=>'Архив аварийных сообщений. Камерные сушила. ',
				'chamber_drier2_alarm_messages'=>'Архив аварийных сообщений КС2. ',
				'graph_temperature'=>'Архив температур. ',
				'default'=>''
			);
			/*$title['mech_working_time']='Время работы механизма. ';
			$title['tech_parameters']='Архив технологических параметров. ';
			$title['chamber_drier1_alarm_messages']='Архив аварийных сообщений КС1. ';
			$title['chamber_drier2_alarm_messages']='Архив аварийных сообщений КС2. ';
			$title['graph_temperature']='Архив температур. ';
			$title['default']='';*/
			//Рендеринг страницы
			$params = array();
			$tmp_params = explode('&', $this->request->param('id','default'));
			foreach($tmp_params as $key => $value)
			{
				$tmp_param = explode('=', $value);
				$params[$tmp_param[0]]=$tmp_param[1];
			}
			$report_type = (isset($params['report_type']))?$params['report_type']:"default";
			$data['report_type'] = $report_type;
			$data['menutitle'] = $title[$report_type].'Отчёты ЦСИ';
			if ($report_type<>'default')
				$content_view = View::factory('csi/reports/inquery_form',$data)->render();
			else $content_view='';
			$this->template->title = $data['menutitle'];
			$menu_view = View::factory('menu/main_menu',$data)->render();
			$this->template->menu = $menu_view;
			$local_menu_view = View::factory('menu/csi_reports_menu')->render();
			$this->template->local_menu = $local_menu_view;
			$this->template->content = $content_view;
		}
//------------------------------------------------------------------------------
		public function action_csireports_result()
		{
			$styles = array('css/table.css'=>'screen',
			                'css/print.css'=>'print');
			$this->template->styles = $styles;
	//		$report_type = $this->request->param('id','default');
//			$date = $this->request->param('id1',date('d-m-Y'));
//			$date2 = $this->request->param('id2','');
//			$id_mech = $this->request->param('id3', 0);
			$params = array();
			$tmp_params = explode('&', $this->request->param('id','default'));
			foreach($tmp_params as $key => $value)
			{
				$tmp_param = explode('=', $value);
				$params[$tmp_param[0]]=$tmp_param[1];
			}
			$report_type = $params['report_type'];
			$date = (isset($params['date1']))?$params['date1']:date('d-m-Y');
			$date2 = (isset($params['date2']))?$params['date2']:date('d-m-Y');
			$id_mech = (isset($params['id_mech']))?$params['id_mech']:0;
			$param_type = (isset($params['param_type']))?$params['param_type']:0;
			$media = (isset($params['media']))?$params['media']:"html";
			$date=str_replace('-','.',$date);
			$date2=str_replace('-','.',$date2);
			$param_type=str_replace('-','.',$param_type);

			$param_name = array(	"PLC.IN_A.01"=>"Камерное сушило 1. Расход природ.газа",
									"PLC.IN_A.02"=>"Камерное сушило 1. Расход воздуха",
									"PLC.IN_A.03"=>"Камерное сушило 1. Давление природ.газа",
									"PLC.IN_A.04"=>"Камерное сушило 1. Давление воздуха",
									"PLC.IN_A.05"=>"Камерное сушило 1. Температура 1",
									"PLC.IN_A.06"=>"Камерное сушило 1. Температура 2",
									"PLC.IN_A.07"=>"Камерное сушило 1. Температура 3",
									"PLC.IN_A.14"=>"Камерное сушило 1. Положение ИМ расхода газа",
									"PLC.IN_A.15"=>"Камерное сушило 1. Положение ИМ расхода воздуха",
									"PLC.IN_A.08"=>"Камерное сушило 2. Расход природ.газа",
									"PLC.IN_A.09"=>"Камерное сушило 2. Расход воздуха",
									"PLC.IN_A.10"=>"Камерное сушило 2. Давление природ.газа",
									"PLC.IN_A.11"=>"Камерное сушило 2. Давление воздуха",
									"PLC.IN_A.12"=>"Камерное сушило 2. Температура 1",
									"PLC.IN_A.13"=>"Камерное сушило 2. Температура 2",
									"PLC.IN_A.17"=>"Камерное сушило 2. Положение ИМ расхода газа",
									"PLC.IN_A.18"=>"Камерное сушило 2. Положение ИМ расхода воздуха",
									"PLC.IN_A.19"=>"Камерное сушило 2. Температура 3",
									"PLC.IN_A.20"=>"Пропарочная камера 1. Температура",
									"PLC.IN_A.21"=>"Пропарочная камера 2. Температура",
									"PLC.IN_A.22"=>"Пропарочная камера 3. Температура",
									"PLC.IN_A.23"=>"Туннельное сушило 2. Температура 1",
									"PLC.IN_A.24"=>"Туннельное сушило 2. Температура 2",
									"PLC.IN_A.25"=>"Туннельное сушило 2. Температура 3",
									"PLC.IN_A.26"=>"Туннельное сушило 2. Температура 4",
									"PLC.IN_A.27"=>"Туннельное сушило 2. Температура 5",
									"PLC.IN_A.28"=>"Пропарочная камера 4. Температура",
									"1890"=>"Сушильный барабан №1. Температура в топке",
									"1891"=>"Сушильный барабан №1. Температура на выходе",
									"1889"=>"Сушильный барабан №1. Разряжение",
									"1888"=>"Сушильный барабан №1. Расход газа",
									"1899"=>"Сушильный барабан №2. Температура в топке",
									"1900"=>"Сушильный барабан №2. Температура на выходе",
									"1893"=>"Сушильный барабан №2. Разряжение",
									"1892"=>"Сушильный барабан №2. Расход газа",
									"3612"=>"Сжатый воздух ЦШИ (Газоочистка). Температура",
									"3613"=>"Сжатый воздух ЦШИ (Газоочистка). Давление",
									"3615"=>"Сжатый воздух ЦШИ (Газоочистка). Расход");
			$y_title_name = array (	"PLC.IN_A.01"=>"Pacxoд, м³/ч",				"PLC.IN_A.02"=>"Pacxoд, 10³ м³/ч",
									"PLC.IN_A.03"=>"Давление, кПа",				"PLC.IN_A.04"=>"Давление, кПа",
									"PLC.IN_A.05"=>"Температура, °С",			"PLC.IN_A.06"=>"Температура, °С",
									"PLC.IN_A.07"=>"Температура, °С",			"PLC.IN_A.14"=>"Положение, %",
									"PLC.IN_A.15"=>"Положение, %",				"PLC.IN_A.08"=>"Pacxoд, м³/ч",
									"PLC.IN_A.09"=>"Pacxoд, 10³ м³/ч",			"PLC.IN_A.10"=>"Давление, кПа",
									"PLC.IN_A.11"=>"Давление, кПа",				"PLC.IN_A.12"=>"Температура, °С",
									"PLC.IN_A.13"=>"Температура, °С",			"PLC.IN_A.17"=>"Положение, %",
									"PLC.IN_A.18"=>"Положение, %",				"PLC.IN_A.19"=>"Температура, °С",
									"PLC.IN_A.20"=>"Температура, °С",			"PLC.IN_A.21"=>"Температура, °С",
									"PLC.IN_A.22"=>"Температура, °С",			"PLC.IN_A.23"=>"Температура, °С",
									"PLC.IN_A.24"=>"Температура, °С",			"PLC.IN_A.25"=>"Температура, °С",
									"PLC.IN_A.26"=>"Температура, °С",			"PLC.IN_A.27"=>"Температура, °С",
									"PLC.IN_A.28"=>"Температура, °С",
									"1890"=>"Температура, °С",	"1891"=>"Температура, °С",
									"1889"=>"Разряжение, Па",	"1888"=>"Pacxoд, м³/ч",
									"1899"=>"Температура, °С",	"1900"=>"Температура, °С",
									"1893"=>"Разряжение, Па",	"1892"=>"Pacxoд, м³/ч",
									"3612"=>"Температура, °С",
									"3613"=>"Давление, кПа",
									"3615"=>"Расход, м³/ч");
			switch ($report_type) {
				case 'chamber_drier1_alarm_messages':
					$report = new Model_Csitpreports();
					$result = $report->csi_chamber_drier1_alarm_report($date, $date2);
					$data['menutitle']='Архив аварийных сообщений. Камерные сушила. ЦСИ';
					$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
				break;
				case 'csi_desiccators':
					$report = new Model_Csitpreports();
					$result = $report->csi_desiccators($date, $date2, $param_type, $y_title_name[$param_type]);
					$data['menutitle']='ЦСИ. Туннельные сушила. '.$param_name[$param_type];
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,43);
				break;
				case 'csi_tunneldry':
					$report = new Model_Csitpreports();
					$result = $report->csi_tunneldry($date, $date2, $param_type, $y_title_name[$param_type]);
					$data['menutitle']='ЦСИ. Сушильные барабаны. '.$param_name[$param_type];
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,43);
				break;
				case 'drier_teh':
					$report = new Model_Csitpreports();
					$result = $report->drier_teh($date, $date2, $param_type, $y_title_name[$param_type]);
					$data['menutitle']='ЦСИ. Сушильные барабаны. '.$param_name[$param_type];
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,43);
				break;
				case 'csi_steamers':
					$report = new Model_Csitpreports();
					$result = $report->drier_teh($date, $date2, $param_type, $y_title_name[$param_type]);
					$data['menutitle']='ЦСИ. Пропарочные камеры. '.$param_name[$param_type];
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,43);
				break;
				case 'csi_tunnel':
					$report = new Model_Csitpreports();
					$result = $report->drier_teh($date, $date2, $param_type, $y_title_name[$param_type]);
					$data['menutitle']='ЦСИ. Туннельные сушила. '.$param_name[$param_type];
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,43);
				break;
			}
			$this->template->title = $data['menutitle'];
			if (count($result['data'])==0)
			{
				$error_data=array('error_topic'=>'Ошибка!','error_message'=>'Нет данных за указанный период времени!');
				$error_message_view = View::factory('/error_message',$error_data)->render();
				$content_view=$error_message_view;
			}
			else if ($media=="xls")
			{
				$ws = new Spreadsheet(array(
					'author' => 'Kohana-PHPExcel',
					'title' => 'Report',
					'subject' => 'Subject',
					'description' => 'Description',
				));
				$ws->set_response($this->response);
				$result['caption'] = $data['menutitle'];
				$result['reportdate'] = strip_tags($note);
				$ws->set_report_data($result,"Отчёт",array(18,41.8,16.3));
				$ws->send(array('name'=>'report', 'format'=>'Excel5'));
			}
			else
			{
				$table = Table::factory($result['data']);
				$table->set_footer($result['footer']);
				$table->set_attributes('id', 'report_table');
				$table->set_attributes('align', 'center');
				$table->set_column_titles($result['column_titles']);
				//$content_view = $note.$table->render().'<br><a href=# onClick="window.print()">распечатать</a> ';
				$table_view = $table->render();
				$data['report_content'] = $table_view;
				$content_view = View::factory('report_result_view',$data)->render();
			}
			$menu_view = View::factory('menu/main_menu',$data)->render();
			$this->template->menu = $menu_view;
			$local_menu_view = View::factory('menu/csi_reports_menu')->render();
			$this->template->local_menu = $local_menu_view;
			$this->template->content = $content_view;
		}
//------------------------------------------------------------------------------
		public function action_cshireports()
		{
			$styles = array('css/calendar/aqua/theme.css'=>'screen');
			$scripts = array('js/calendar/calendar-setup.js','js/calendar/lang/calendar-ru.js','js/calendar/calendar.js','js/calendar/checkDate.js',);
			$this->template->styles = $styles;
			$this->template->scripts = $scripts;
//			$id = $this->request->param('id','default');
			$params = array();
			$tmp_params = explode('&', $this->request->param('id','default'));
			foreach($tmp_params as $key => $value)
			{
				$tmp_param = explode('=', $value);
				$params[$tmp_param[0]]=$tmp_param[1];
			}
			$id = $params['id'];
			$data['id']=$id;
			switch ($id) {
				case 'cshi_tpl_mech_working_time':
					$data['menutitle']='ЦШИ. Время работы механизма.';
					$content_view = View::factory('cshi/reports/cshi_tpl_mech_working_time',$data)->render();
				break;
				case 'cshi_rotary_furn_mech_working_time':
					$data['menutitle']='ЦШИ. Время работы механизма.';
					$content_view = View::factory('cshi/reports/cshi_rotary_furn_mech_working_time',$data)->render();
				break;
				case 'cshi_tpl_marsh_state':
					$data['menutitle']='ЦШИ. Состояния маршрута.';
					$content_view = View::factory('cshi/reports/cshi_tpl_marsh_state',$data)->render();
				break;
				case 'cshi_tpl_refregirator_stop_time':
					$data['menutitle']='ЦШИ. Время простоя холодильника.';
					$content_view = View::factory('cshi/reports/inquery_form',$data)->render();
				break;
				case 'cshi_press2_alarm':
					$data['menutitle']='ЦШИ. ПФУ. Пресс №2. Архив аварийных сообщений.';
					$content_view = View::factory('cshi/reports/inquery_form',$data)->render();
				break;
				case 'cshi_press5_alarm':
					$data['menutitle']='ЦШИ. ПФУ. Пресс №5. Архив аварийных сообщений.';
					$content_view = View::factory('cshi/reports/inquery_form',$data)->render();
				break;
				case 'cshi_press3_alarm':
					$data['menutitle']='ЦШИ. ПФУ. Пресс №3. Архив аварийных сообщений.';
					$content_view = View::factory('cshi/reports/inquery_form',$data)->render();
				break;
				case 'recipe_archive':
					$data['menutitle']='ЦШИ. ПФУ. Пресс №2. Архив рецептов.';
					$content_view = View::factory('cshi/reports/inquery_form',$data)->render();
				break;
				case 'line_5_recipe_archive':
					$data['menutitle']='ЦШИ. ПФУ. Пресс №5. Архив рецептов.';
					$content_view = View::factory('cshi/reports/inquery_form',$data)->render();
				break;
				case 'line_2_balancing_archive':
					$data['menutitle']='ЦШИ. ПФУ. Пресс №2. Архив дозирования материалов.';
					$content_view = View::factory('cshi/reports/cshi_pfu_press_line_2_balancing_archive',$data)->render();
				break;
				case 'line_5_balancing_archive':
					$data['menutitle']='ЦШИ. ПФУ. Пресс №5. Архив замесов.';
					$content_view = View::factory('cshi/reports/cshi_pfu_press_line_5_balancing_archive',$data)->render();
				break;
				case 'line_5_balancing_archive2':
					$data['menutitle']='ЦШИ. ПФУ. Пресс №5. Архив дозирования весов.';
					$content_view = View::factory('cshi/reports/cshi_pfu_press_line_5_balancing_archive2',$data)->render();
				break;
				case 'line_6_balancing_archive':
					$data['menutitle']='ЦШИ. ПФУ. Пресс №6. Архив дозирования материалов.';
					$content_view = View::factory('cshi/reports/cshi_pfu_press_line_6_balancing_archive',$data)->render();
				break;
				case 'cshi_tpl_vent_system_working_time':
					$data['menutitle']='ЦШИ. ТПЛ. Учет работы пылегазоулавливающей установки ДПО';
					$content_view = View::factory('cshi/reports/inquery_form',$data)->render();
				break;
				case 'cshi_tpl_rorate_furnace_working_time':
					$data['menutitle']='ЦШИ. ТПЛ. Время работы вращающихся печей в режиме холостого хода';
					$content_view = View::factory('cshi/reports/inquery_form',$data)->render();
				break;
				case 'cshi_tpl_alarm':
					$data['menutitle']='ЦШИ. ТПЛ. Архив аварийных сообщений';
					$content_view = View::factory('cshi/reports/inquery_form',$data)->render();
				break;
				case 'cshi_tp1_mech_working_time':
					$data['menutitle']='ЦШИ. ТП1. Время работы механизма';
					$content_view = View::factory('cshi/reports/cshi_tp1_mech_working_time',$data)->render();
				break;
				case 'cshi_line_5_mech_working_time':
					$data['menutitle']='ЦШИ. ПФУ. Линия прессования №5. Время работы механизма';
					$content_view = View::factory('cshi/reports/cshi_line_5_mech_working_time',$data)->render();
				break;
				case 'cshi_pfu_mech_working_time':
					$data['menutitle']='ЦШИ. ПФУ. Время работы механизма';
					$content_view = View::factory('cshi/reports/cshi_pfu_mech_working_time',$data)->render();
				break;
				case 'mixer_stakan_current':
					$data['menutitle']='ЦШИ. ПФУ. График тока стакана смесителя';
					$content_view = View::factory('cshi/reports/mixer_stakan_current',$data)->render();
				break;
				case 'mixer_zavih_current':
					$data['menutitle']='ЦШИ. ПФУ. График тока завихрителя смесителя';
					$content_view = View::factory('cshi/reports/mixer_stakan_current',$data)->render();
				break;
				case 'press_current':
					$data['menutitle']='ЦШИ. ПФУ. График тока прессования';
					$content_view = View::factory('cshi/reports/mixer_stakan_current',$data)->render();
				break;
				case 'press6_current':
					$data['menutitle']='ЦШИ. ПФУ.Пресс 6. График тока прессования';
					$content_view = View::factory('cshi/reports/mixer_stakan_current',$data)->render();
				break;
				case 'press7_current':
					$data['menutitle']='ЦШИ. ПФУ.Пресс 7. График тока прессования';
					$content_view = View::factory('cshi/reports/mixer_stakan_current',$data)->render();
				break;
				case 'press3_current':
					$data['menutitle']='ЦШИ. ПФУ.Пресс 3. График тока прессования';
					$content_view = View::factory('cshi/reports/mixer_stakan_current',$data)->render();
				break;
				case 'press9_current':
					$data['menutitle']='ЦШИ. ПФУ.Пресс 9. График тока прессования';
					$content_view = View::factory('cshi/reports/mixer_stakan_current',$data)->render();
				break;
				case 'press4_current':
					$data['menutitle']='ЦШИ. ПФУ.Пресс 4. График тока прессования';
					$content_view = View::factory('cshi/reports/mixer_stakan_current',$data)->render();
				break;
				case 'press8_current':
					$data['menutitle']='ЦШИ. ПФУ.Пресс 8. График тока прессования';
					$content_view = View::factory('cshi/reports/mixer_stakan_current',$data)->render();
				break;
				case 'press10_current':
					$data['menutitle']='ЦШИ. ПФУ.Пресс 10. График тока прессования';
					$content_view = View::factory('cshi/reports/mixer_stakan_current',$data)->render();
				break;
				case 'press11_current':
					$data['menutitle']='ЦШИ. ПФУ.Пресс 11. График тока прессования';
					$content_view = View::factory('cshi/reports/mixer_stakan_current',$data)->render();
				break;
				case 'tp1_teh':
					$data['menutitle']='ЦШИ. Туннельная печь №1. Архив технологических параметров';
					$content_view = View::factory('cshi/reports/inquery_form_teh',$data)->render();
				break;
				case 'cshi_dry4':
					$data['menutitle']='ЦШИ. Сушило №4. Архив технологических параметров';
					$content_view = View::factory('cshi/reports/cshi_dry4_inquery',$data)->render();
				break;
				case 'cshi_rotary_furn':
					$data['menutitle']='ЦШИ. Вращающаяся печь №1. Архив технологических параметров';
					$content_view = View::factory('cshi/reports/cshi_rotary_furn',$data)->render();
				break;
				case 'cshi_rotary_furn2':
					$data['menutitle']='ЦШИ. Вращающаяся печь №2. Архив технологических параметров';
					$content_view = View::factory('cshi/reports/cshi_rotary_furn2',$data)->render();
				break;
				case 'cshi_tunneldry':
					$data['menutitle']='ЦШИ. Туннельные сушила. Архив технологических параметров';
					$content_view = View::factory('cshi/reports/cshi_tunneldry',$data)->render();
				break;
				case 'cshi_carts':
					$data['menutitle']='ЦШИ. Вагонные весы. Архив паспортов вагонов';
					$content_view = View::factory('cshi/reports/cshi_carts',$data)->render();
				break;
				default:
					$data['menutitle']='Отчёты ЦШИ';
					$content_view = View::factory('cshi/reports/default',$data)->render();
				break;
				case 'cshi_carts_avtorization':
					$data['menutitle']='ЦШИ. Вагонные весы. Авторизация пользователя';
					$content_view = View::factory('cshi/reports/cshi_carts_avtorization',$data)->render();
				break;
				case 'cshi_balloon':
					$data['menutitle']='ЦШИ. Балоны. Архив технологических параметров';
					$content_view = View::factory('cshi/reports/cshi_balloon',$data)->render();
				break;
				case 'cshi_conveyor_scales':
					$data['menutitle']='ЦШИ. Конвейерные весы. Архив технологических параметров';
					$content_view = View::factory('cshi/reports/cshi_conveyor_scales',$data)->render();
				break;
				case 'cshi_conveyor_scales2':
					$data['menutitle']='ЦШИ. Конвейерные весы. Архив технологических параметров';
					$content_view = View::factory('cshi/reports/cshi_conveyor_scales2',$data)->render();
				break;
				case 'cshi_desiccators':
					$data['menutitle']='ЦШИ. Сушильные барабаны. Архив технологических параметров';
					$content_view = View::factory('cshi/reports/cshi_desiccators',$data)->render();
				break;
				case 'cshi_line_3_mech_working_time':
					$data['menutitle']='ЦШИ. ПФУ. Пресс №3. Время работы механизма';
					$content_view = View::factory('cshi/reports/cshi_line_3_mech_working_time',$data)->render();
				break;
				case 'line_3_recipe_archive':
					$data['menutitle']='ЦШИ. ПФУ. Пресс №3. Архив рецепта';
					$content_view = View::factory('cshi/reports/inquery_form',$data)->render();
				break;
				case 'cshi_press3_alarm':
					$data['menutitle']='ЦШИ. ПФУ. Пресс №3. Архив аварийных сообщений';
					$content_view = View::factory('cshi/reports/inquery_form',$data)->render();
				break;
				case 'line_3_balancing_archive':
					$data['menutitle']='ЦШИ. ПФУ. Пресс №3. Архив замесов';
					$content_view = View::factory('cshi/reports/cshi_pfu_press_line_3_balancing_archive',$data)->render();
				break;
				case 'line_3_balancing_archive2':
					$data['menutitle']='ЦШИ. ПФУ. Пресс №3. Архив дозирования весов';
					$content_view = View::factory('cshi/reports/cshi_pfu_press_line_3_balancing_archive2',$data)->render();
				break;
				case 'pfu_press_current':
					$data['menutitle']='ЦШИ. ПФУ. Токи прессования';
					$content_view = View::factory('cshi/reports/cshi_pfu_press_current',$data)->render();
				break;
				case 'pfu_press_current_overload':
					$data['menutitle']='ЦШИ. ПФУ. Перегруз пресса';
					$content_view = View::factory('cshi/reports/inquery_form',$data)->render();
				break;
				case 'rotary_furn_brigade':
					$data['menutitle']='ЦШИ. Отделение вращающихся печей. Система бригадных учётов';
					$content_view = View::factory('cshi/reports/rotary_furn_brigade',$data)->render();
				break;
				case 'rotary_furn_brigade2':
					$data['menutitle']='ЦШИ. Отделение вращающихся печей. Система бригадных учётов';
					$content_view = View::factory('cshi/reports/rotary_furn_brigade2',$data)->render();
				break;
			}
			$this->template->title = $data['menutitle'];
			$menu_view = View::factory('menu/main_menu',$data)->render();
			$this->template->menu = $menu_view;
			$local_menu_view = View::factory('menu/cshi_reports_menu')->render();
			$this->template->local_menu = $local_menu_view;
			$this->template->content = $content_view;
		}
//------------------------------------------------------------------------------
		public function action_cshireports_result()
		{
			$styles = array('css/table.css'=>'screen',
                			'css/print.css'=>'print');
			$this->template->styles = $styles;
			$params = array();
			$tmp_params = explode('&', $this->request->param('id','default'));
			foreach($tmp_params as $key => $value)
			{
				$tmp_param = explode('=', $value);
				$params[$tmp_param[0]]=$tmp_param[1];
			}
			$report_type = (isset($params['report_type']))?$params['report_type']:'default';
			$id1 = (isset($params['date1']))?$params['date1']:date('d-m-Y');
			$id2 = (isset($params['date2']))?$params['date2']:'';
			$mech_id = (isset($params['mech_id']))?$params['mech_id']:0;
			$mech_name = (isset($params['mech_name']))?$params['mech_name']:'';
			$route_number = (isset($params['route_number']))?$params['route_number']:'';
			$scale_number = (isset($params['scale_number']))?$params['scale_number']:'ALL';
			$press_number = (isset($params['press_number']))?$params['press_number']:'3';
			$param_type = (isset($params['param_type']))?$params['param_type']:'';
			$media = (isset($params['media']))?$params['media']:'html';
			$zone=(isset($params['zone']))?$params['zone']:'5';
			$deviation=(isset($params['deviation']))?$params['deviation']:'ALL';
			$limit_current=(isset($params['limit_current']))?$params['limit_current']:'75';
			$brigade=(isset($params['brigade']))?$params['brigade']:'0';
			$furnace=(isset($params['furnace']))?$params['furnace']:'0';
			$date=str_replace('-','.',$id1);
			$date2=str_replace('-','.',$id2);
			$param_type=str_replace('-','.',$param_type);
			$zone=str_replace('_','.',$zone);
			$deviation=str_replace('_','.',$deviation);

			$param_name = array(	"T.T_SIGNAL_01"=>"Температура сушила на позиции 3С(Л)",
									"T.T_SIGNAL_02"=>"Температура сушила на позиции 3С(П)",
									"T.T_SIGNAL_03"=>"Температура печи на позиции 5(Л)",
									"T.T_SIGNAL_04"=>"Температура печи на позиции 5(П)",
									"T.T_SIGNAL_05"=>"Температура печи на позиции 9(Л)",
									"T.T_SIGNAL_06"=>"Температура печи на позиции 13(Л)",
									"T.T_SIGNAL_07"=>"Температура печи на позиции 15(П)",
									"T.T_SIGNAL_08"=>"Температура печи на позиции 15(Л)",
									"T.T_SIGNAL_09"=>"Температура печи на позиции 18(П)",
									"T.T_SIGNAL_10"=>"Температура печи на позиции 19(Л)",
									"T.T_SIGNAL_11"=>"Температура печи на позиции 21(Л)",
									"T.T_SIGNAL_12"=>"Температура печи на позиции 22(Л)",
									"T.T_SIGNAL_13"=>"Температура печи на позиции 24(П)",
									"T.T_SIGNAL_14"=>"Температура печи на позиции 29(Л)",
									"T.T_SIGNAL_15"=>"Температура печи на позиции 35(П)",
									"T.T_SIGNAL_17"=>"Температура печи на позиции 40(Л)",
									"T.T_SIGNAL_18"=>"Температура отх. воз. поз 30.31",
									"T.T_SIGNAL_19"=>"Температура отх. воз. поз 34.35",
									"T.T_SIGNAL_24"=>"Температура рец. воздуха",
									"T.T_SIGNAL_25"=>"Температура отх. газов",
									"T.T_SIGNAL_26"=>"Температура сушила на позиции 10(Л)",
									"T.T_SIGNAL_27"=>"Температура сушила на позиции 10(П)",
									"T.T_SIGNAL_33"=>"Температура свода печи на позиции 15(СВ)",
									"T.T_SIGNAL_34"=>"Температура свода печи на позиции 20(СВ)",
									"T.T_SIGNAL_35"=>"Температура свода печи на позиции 23(СВ)",
									"T.T_SIGNAL_37"=>"Температура свода сушила №4",
									"T.T_SIGNAL_38"=>"Температура подаваемого воздуха сушила №4",
									"T.T_SIGNAL_39"=>"Температура сушила №4 бокавая (П)",
									"T.T_SIGNAL_40"=>"Температура сушила №4 бокавая (Л)",
									"T.T_SIGNAL_41"=>"Температура ТП1 23 левая",
									"T.T_SIGNAL_42"=>"Температура ТП1 22 правая",
									"T.T_SIGNAL_43"=>"Температура ТП3 23 левая",
									"T.T_SIGNAL_44"=>"Температура ТП3 22 правая",
									"F.F_SIGNAL_01"=>"Расход газа зона 1 Левая",
									"F.F_SIGNAL_02"=>"Расход газа зона 1 Правая",
									"F.F_SIGNAL_03"=>"Расход газа зона 2 Левая",
									"F.F_SIGNAL_04"=>"Расход газа зона 2 Правая",
									"F.F_SIGNAL_05"=>"Расход газа зона 3 Левая",
									"F.F_SIGNAL_06"=>"Расход газа зона 3 Правая",
									"F.F_SIGNAL_07"=>"Расход воздуха зона 1 Левая",
									"F.F_SIGNAL_08"=>"Расход воздуха зона 1 Правая",
									"F.F_SIGNAL_09"=>"Расход воздуха зона 2 Левая",
									"F.F_SIGNAL_10"=>"Расход воздуха зона 2 Правая",
									"F.F_SIGNAL_11"=>"Расход воздуха зона 3 Левая",
									"F.F_SIGNAL_12"=>"Расход воздуха зона 3 Правая",
									"F.F_SIGNAL_13"=>"Расход воздуха (Распределенная подача)",
									"F.F_SIGNAL_14"=>"Расход воздуха (Сосредоточенная подача)",
									"F.F_SIGNAL_15"=>"Расход воздуха (Рециркуляционный)",
									"F.F_SIGNAL_16"=>"Расход коксового газа",
									"F.F_SIGNAL_17"=>"Расход воздуха",
									"P.P_SIGNAL_01"=>"Давление в сушиле на позиции 3С",
									"P.P_SIGNAL_02"=>"Давление в сушиле на позиции 10С",
									"P.P_SIGNAL_04"=>"Давление в печи на позиции 5",
									"P.P_SIGNAL_05"=>"Давление в печи на позиции 15",
									"P.P_SIGNAL_08"=>"Давление в печи на позиции 33",
									"P.P_SIGNAL_09"=>"Давление в печи на позиции 38",
									"P.P_SIGNAL_10"=>"Давление газа в печь",
									"P.P_SIGNAL_11"=>"Давление отходящих газов",
									"P.P_SIGNAL_12"=>"Давление воздуха на горение",
									"P.P_SIGNAL_13"=>"Давление в зоне охлаждения",
									"P.P_SIGNAL_14"=>"Разряжение в зоне нагрева",
									"T.T_SIGNAL_36"=>"Показания пирометра",
									"PLC.IN_A.01"=>"Камерное сушило 1. Расход природ.газа",
									"PLC.IN_A.02"=>"Камерное сушило 1. Расход воздуха",
									"PLC.IN_A.03"=>"Камерное сушило 1. Давление природ.газа",
									"PLC.IN_A.04"=>"Камерное сушило 1. Давление воздуха",
									"PLC.IN_A.05"=>"Камерное сушило 1. Температура 1",
									"PLC.IN_A.06"=>"Камерное сушило 1. Температура 2",
									"PLC.IN_A.07"=>"Камерное сушило 1. Температура 3",
									"PLC.IN_A.14"=>"Камерное сушило 1. Положение ИМ расхода газа",
									"PLC.IN_A.15"=>"Камерное сушило 1. Положение ИМ расхода воздуха",
									"PLC.IN_A.08"=>"Камерное сушило 2. Расход природ.газа",
									"PLC.IN_A.09"=>"Камерное сушило 2. Расход воздуха",
									"PLC.IN_A.10"=>"Камерное сушило 2. Давление природ.газа",
									"PLC.IN_A.11"=>"Камерное сушило 2. Давление воздуха",
									"PLC.IN_A.12"=>"Камерное сушило 2. Температура 1",
									"PLC.IN_A.13"=>"Камерное сушило 2. Температура 2",
									"PLC.IN_A.17"=>"Камерное сушило 2. Положение ИМ расхода газа",
									"PLC.IN_A.18"=>"Камерное сушило 2. Положение ИМ расхода воздуха",
									"PLC.IN_A.19"=>"Камерное сушило 2. Температура 3",
									"PLC.IN_A.20"=>"Пропарочная камера 1. Температура",
									"PLC.IN_A.21"=>"Пропарочная камера 2. Температура",
									"PLC.IN_A.22"=>"Пропарочная камера 3. Температура",
									"PLC.IN_A.23"=>"Туннельное сушило 2. Температура 1",
									"PLC.IN_A.24"=>"Туннельное сушило 2. Температура 2",
									"PLC.IN_A.25"=>"Туннельное сушило 2. Температура 3",
									"PLC.IN_A.26"=>"Туннельное сушило 2. Температура 4",
									"PLC.IN_A.27"=>"Туннельное сушило 2. Температура 5",
									"PLC.IN_A.28"=>"Пропарочная камера 4. Температура",
									"3208"=>"Разряжение перед циклоном (лев.)",
									"3209"=>"Разряжение перед циклоном (прав.)",
									"3210"=>"Разряжение перед скруббером",
									"3211"=>"Разряжение перед дымососом",
									"3212"=>"Разряжение перед эл. фильтром (лев.)",
									"3213"=>"Разряжение перед эл. фильтром (прав.)",
									"3214"=>"Разряжение в пылевой камере",
									"3215"=>"Температура перед эл. фильтром (лев.)",
									"3216"=>"Температура перед эл. фильтром (прав.)",
									"3217"=>"Температура перед дымососом",
									"3218"=>"Температура в пылевой камере",
									"3219"=>"Анализ на СО",
									"3220"=>"Температура перед циклоном (лев.)",
									"3221"=>"Температура перед циклоном (прав.)",
									"3222"=>"Нагрузка питателя",
									"3224"=>"Q вентиляторного воздуха",
									"3174"=>"Сушильный барабан №1. Температура в топке",
									"3175"=>"Сушильный барабан №1. Температура на выходе",
									"3176"=>"Сушильный барабан №1. Разряжение",
									"3177"=>"Сушильный барабан №2. Температура в топке",
									"3178"=>"Сушильный барабан №2. Температура на выходе",
									"3179"=>"Сушильный барабан №2. Разряжение",
									"3180"=>"Сушильный барабан №3. Температура в топке",
									"3181"=>"Сушильный барабан №3. Температура на выходе",
									"3182"=>"Сушильный барабан №3. Разряжение",
									"1890"=>"Сушильный барабан №1. Температура в топке",
									"1891"=>"Сушильный барабан №1. Температура на выходе",
									"1889"=>"Сушильный барабан №1. Разряжение",
									"1888"=>"Сушильный барабан №1. Расход газа",
									"1899"=>"Сушильный барабан №2. Температура в топке",
									"1900"=>"Сушильный барабан №2. Температура на выходе",
									"1893"=>"Сушильный барабан №2. Разряжение",
									"1892"=>"Сушильный барабан №2. Расход газа",
									"3612"=>"Сжатый воздух ЦШИ (Газоочистка). Температура",
									"3613"=>"Сжатый воздух ЦШИ (Газоочистка). Давление",
									"3615"=>"Сжатый воздух ЦШИ (Газоочистка). Расход",
									"IN_A.IN_A_1"=>"Общая температура газа",
									"IN_A.IN_A_2"=>"Общее давление газа",
									"IN_A.IN_A_3"=>"Разряжение в пылевой камере",
									"IN_A.IN_A_4"=>"Разряжение перед скруббером",
									"IN_A.IN_A_5"=>"Разряжение перед циклоном левый канал",
									"IN_A.IN_A_6"=>"Разряжение перед циклоном правый канал",
									"IN_A.IN_A_7"=>"Разряжение перед электрофильтром левый канал",
									"IN_A.IN_A_8"=>"Разряжение перед электрофильтром правый канал",
									"IN_A.IN_A_9"=>"Разряжение перед дымососом",
									"IN_A.IN_A_10"=>"Температура в пылевой камере",
									"IN_A.IN_A_11"=>"Анализ на «СО»",
									"IN_A.IN_A_12"=>"Температура перед циклоном левый канал",
									"IN_A.IN_A_13"=>"Температура перед циклоном правый канал",
									"IN_A.IN_A_14"=>"Температура перед электрофильтром левый канал",
									"IN_A.IN_A_15"=>"Температура перед электрофильтром правый канал",
									"IN_A.IN_A_16"=>"Температура перед дымососом",
									"IN_A.IN_A_17"=>"Расход вентиляционного воздуха",
									"IN_A.IN_A_18"=>"Расход газа",
									"IN_A.IN_A_19"=>"Давление газа",
									"IN_A.IN_A_20"=>"Весы",
									"IN_A.IN_A_21"=>"Нагрузка питателя",
									"IN_A.IN_A_22"=>"Уровень в бункере выгрузки пыли",
									"IN_A.IN_A_23"=>"ИМ газ",
									"IN_A.IN_A_24"=>"ИМ воздух",
									"IN_A.IN_A_25"=>"Расход сжатого воздуха на газоочистку",
									"IN_A.IN_A_34"=>"Разрежение в пылевой камере",
									"IN_A.IN_A_35"=>"Разрежение перед скруббером",
									"IN_A.IN_A_36"=>"Разрежение перед циклоном левый канал",
									"IN_A.IN_A_37"=>"Разрежение перед циклоном правый канал",
									"IN_A.IN_A_38"=>"Разрежение перед электрофильтром левый канал",
									"IN_A.IN_A_39"=>"Разрежение перед электрофильтром правый канал",
									"IN_A.IN_A_40"=>"Разрежение перед дымососом",
									"IN_A.IN_A_41"=>"Температура в пылевой камере",
									"IN_A.IN_A_42"=>"Анализ на СО",
									"IN_A.IN_A_43"=>"Температура перед циклоном левый канал",
									"IN_A.IN_A_44"=>"Температура перед циклоном правый канал",
									"IN_A.IN_A_45"=>"Температура перед электрофильтром левый канал",
									"IN_A.IN_A_46"=>"Температура перед электрофильтром правый канал",
									"IN_A.IN_A_47"=>"Температура перед дымососом",
									"IN_A.IN_A_48"=>"Расход вентиляторного воздуха",
									"IN_A.IN_A_49"=>"Расход газа",
									"IN_A.IN_A_50"=>"Давление газа",
									"IN_A.IN_A_51"=>"Весы",
									"IN_A.IN_A_52"=>"Нагрузка питателя",
									"IN_A.IN_A_53"=>"Положение ИМ расхода газа",
									"IN_A.IN_A_54"=>"Положение ИМ расхода воздуха",
									"1"=>"Весовой конвейер №7",
									"2"=>"Весовой конвейер №17",
									"3"=>"Весовой конвейер №29",
									"4"=>"Весовой конвейер №39",
									"5"=>"Весовой конвейер №53");


			$y_title_name = array (	"T.T_SIGNAL_01"=>"Температура, °С",			"T.T_SIGNAL_02"=>"Температура, °С",
									"T.T_SIGNAL_03"=>"Температура, °С",			"T.T_SIGNAL_04"=>"Температура, °С",
									"T.T_SIGNAL_05"=>"Температура, °С",			"T.T_SIGNAL_06"=>"Температура, °С",
									"T.T_SIGNAL_07"=>"Температура, °С",			"T.T_SIGNAL_08"=>"Температура, °С",
									"T.T_SIGNAL_09"=>"Температура, °С",			"T.T_SIGNAL_10"=>"Температура, °С",
									"T.T_SIGNAL_11"=>"Температура, °С",			"T.T_SIGNAL_12"=>"Температура, °С",
									"T.T_SIGNAL_13"=>"Температура, °С",			"T.T_SIGNAL_14"=>"Температура, °С",
									"T.T_SIGNAL_15"=>"Температура, °С",			"T.T_SIGNAL_17"=>"Температура, °С",
									"T.T_SIGNAL_18"=>"Температура, °С",			"T.T_SIGNAL_19"=>"Температура, °С",
									"T.T_SIGNAL_24"=>"Температура, °С",			"T.T_SIGNAL_25"=>"Температура, °С",
									"T.T_SIGNAL_26"=>"Температура, °С",			"T.T_SIGNAL_27"=>"Температура, °С",
									"T.T_SIGNAL_33"=>"Температура, °С",			"T.T_SIGNAL_34"=>"Температура, °С",
									"T.T_SIGNAL_35"=>"Температура, °С",			"F.F_SIGNAL_01"=>"Pacxoд, м³/ч",
									"F.F_SIGNAL_02"=>"Pacxoд, м³/ч",			"F.F_SIGNAL_03"=>"Pacxoд, м³/ч",
									"F.F_SIGNAL_04"=>"Pacxoд, м³/ч",			"F.F_SIGNAL_05"=>"Pacxoд, м³/ч",
									"F.F_SIGNAL_06"=>"Pacxoд, м³/ч",			"F.F_SIGNAL_07"=>"Pacxoд, м³/ч",
									"F.F_SIGNAL_08"=>"Pacxoд, м³/ч",			"F.F_SIGNAL_09"=>"Pacxoд, м³/ч",
									"F.F_SIGNAL_10"=>"Pacxoд, м³/ч",			"F.F_SIGNAL_11"=>"Pacxoд, м³/ч",
									"F.F_SIGNAL_12"=>"Pacxoд, м³/ч",			"F.F_SIGNAL_13"=>"Pacxoд, м³/ч",
									"F.F_SIGNAL_14"=>"Pacxoд, м³/ч",			"F.F_SIGNAL_15"=>"Pacxoд, м³/ч",
									"F.F_SIGNAL_16"=>"Pacxoд, м³/ч",			"F.F_SIGNAL_17"=>"Pacxoд, м³/ч",
									"P.P_SIGNAL_01"=>"Давление, кгс/м²",		"P.P_SIGNAL_02"=>"Давление, кгс/м²",
									"P.P_SIGNAL_04"=>"Давление, кгс/м²",		"P.P_SIGNAL_05"=>"Давление, кгс/м²",
									"P.P_SIGNAL_08"=>"Давление, кгс/м²",		"P.P_SIGNAL_09"=>"Давление, кгс/м²",
									"P.P_SIGNAL_10"=>"Давление, кгс/м²",		"P.P_SIGNAL_11"=>"Давление, кгс/м²",
									"P.P_SIGNAL_12"=>"Давление, кгс/м²",		"P.P_SIGNAL_13"=>"Давление, кгс/м²",
									"P.P_SIGNAL_14"=>"Давление, кгс/м²",		"T.T_SIGNAL_34"=>"Температура, °С",
									"T.T_SIGNAL_36"=>"Температура, °С",
									"T.T_SIGNAL_37"=>"Температура, °С",
									"T.T_SIGNAL_38"=>"Температура, °С",
									"T.T_SIGNAL_39"=>"Температура, °С",
									"T.T_SIGNAL_40"=>"Температура, °С",
									"T.T_SIGNAL_41"=>"Температура, °С",
									"T.T_SIGNAL_42"=>"Температура, °С",
									"T.T_SIGNAL_43"=>"Температура, °С",
									"T.T_SIGNAL_44"=>"Температура, °С",
									"PLC.IN_A.01"=>"Pacxoд, м³/ч",				"PLC.IN_A.02"=>"Pacxoд, 10³ м³/ч",
									"PLC.IN_A.03"=>"Давление, кПа",				"PLC.IN_A.04"=>"Давление, кПа",
									"PLC.IN_A.05"=>"Температура, °С",			"PLC.IN_A.06"=>"Температура, °С",
									"PLC.IN_A.07"=>"Температура, °С",			"PLC.IN_A.14"=>"Положение, %",
									"PLC.IN_A.15"=>"Положение, %",				"PLC.IN_A.08"=>"Pacxoд, м³/ч",
									"PLC.IN_A.09"=>"Pacxoд, м³/ч",				"PLC.IN_A.10"=>"Давление, кПа",
									"PLC.IN_A.11"=>"Давление, кПа",				"PLC.IN_A.12"=>"Температура, °С",
									"PLC.IN_A.13"=>"Температура, °С",			"PLC.IN_A.17"=>"Положение, %",
									"PLC.IN_A.18"=>"Положение, %",				"PLC.IN_A.19"=>"Температура, °С",
									"PLC.IN_A.20"=>"Температура, °С",			"PLC.IN_A.21"=>"Температура, °С",
									"PLC.IN_A.22"=>"Температура, °С",			"PLC.IN_A.23"=>"Температура, °С",
									"PLC.IN_A.24"=>"Температура, °С",			"PLC.IN_A.25"=>"Температура, °С",
									"PLC.IN_A.26"=>"Температура, °С",			"PLC.IN_A.27"=>"Температура, °С",
									"PLC.IN_A.28"=>"Температура, °С",			"3208"=>"Разряжение, Па",
									"3209"=>"Разряжение, Па",					"3210"=>"Разряжение, Па",
									"3211"=>"Разряжение, Па",					"3212"=>"Разряжение, Па",
									"3213"=>"Разряжение, Па",					"3214"=>"Разряжение, Па",
									"3215"=>"Температура, °С",					"3216"=>"Температура, °С",
									"3217"=>"Температура, °С",					"3218"=>"Температура, °С",
									"3219"=>"CO",								"3220"=>"Температура, °С",
									"3221"=>"Температура, °С",					"3222"=>"Нагрузка, В",
									"3223"=>"Q, м3/ч",							"3174"=>"Температура, °С",
									"3175"=>"Температура, °С",					"3176"=>"Разряжение, Па",
									"3177"=>"Температура, °С",					"3178"=>"Температура, °С",
									"3179"=>"Разряжение, Па",					"3180"=>"Температура, °С",
									"3181"=>"Температура, °С",					"3182"=>"Разряжение, Па",
									"1890"=>"Температура, °С",					"1891"=>"Температура, °С",
									"1889"=>"Разряжение, Па",					"1888"=>"Pacxoд, м³/ч",
									"1899"=>"Температура, °С",					"1900"=>"Температура, °С",
									"1893"=>"Разряжение, Па",					"1892"=>"Pacxoд, м³/ч",
									"3612"=>"Температура, °С",
									"3613"=>"Давление, кПа",
									"361"=>"Расход, м³/ч",

									"IN_A.IN_A_1"=>"Температура, °С",
									"IN_A.IN_A_2"=>"Давление, кПа",
									"IN_A.IN_A_3"=>"Разряжение, Па",
									"IN_A.IN_A_4"=>"Разряжение, Па",
									"IN_A.IN_A_5"=>"Разряжение, Па",
									"IN_A.IN_A_6"=>"Разряжение, Па",
									"IN_A.IN_A_7"=>"Разряжение, Па",
									"IN_A.IN_A_8"=>"Разряжение, Па",
									"IN_A.IN_A_9"=>"Разряжение, Па",
									"IN_A.IN_A_10"=>"Температура, °С",
									"IN_A.IN_A_11"=>"%",
									"IN_A.IN_A_12"=>"Температура, °С",
									"IN_A.IN_A_13"=>"Температура, °С",
									"IN_A.IN_A_14"=>"Температура, °С",
									"IN_A.IN_A_15"=>"Температура, °С",
									"IN_A.IN_A_16"=>"Температура, °С",
									"IN_A.IN_A_17"=>"Pacxoд, м³/ч",
									"IN_A.IN_A_18"=>"Pacxoд, м³/ч",
									"IN_A.IN_A_19"=>"Давление, Па",
									"IN_A.IN_A_20"=>"%",
									"IN_A.IN_A_21"=>"Нагрузка, В",
									"IN_A.IN_A_22"=>"Уровень, %",
									"IN_A.IN_A_23"=>"%",
									"IN_A.IN_A_24"=>"%",
									"IN_A.IN_A_25"=>"Pacxoд, м³/ч",
									"IN_A.IN_A_34"=>"Разряжение, Па",
									"IN_A.IN_A_35"=>"Разряжение, Па",
									"IN_A.IN_A_36"=>"Разряжение, Па",
									"IN_A.IN_A_37"=>"Разряжение, Па",
									"IN_A.IN_A_38"=>"Разряжение, Па",
									"IN_A.IN_A_39"=>"Разряжение, Па",
									"IN_A.IN_A_40"=>"Разряжение, Па",
									"IN_A.IN_A_41"=>"Температура, °С",
									"IN_A.IN_A_42"=>"%",
									"IN_A.IN_A_43"=>"Температура, °С",
									"IN_A.IN_A_44"=>"Температура, °С",
									"IN_A.IN_A_45"=>"Температура, °С",
									"IN_A.IN_A_46"=>"Температура, °С",
									"IN_A.IN_A_47"=>"Температура, °С",
									"IN_A.IN_A_48"=>"Pacxoд, м³/ч",
									"IN_A.IN_A_49"=>"Pacxoд, м³/ч",
									"IN_A.IN_A_50"=>"Давление, Па",
									"IN_A.IN_A_51"=>"%",
									"IN_A.IN_A_52"=>"Нагрузка, В",
									"IN_A.IN_A_53"=>"Положение, %",
									"IN_A.IN_A_54"=>"Положение, %");


			$note = '';
			$column_width=array(0,0,0,0,0);
			switch ($report_type) {
				case 'cshi_tpl_mech_working_time':
					$report = new Model_CshiTPLReports();
					$result = $report->cshi_tpl_mech_working_time_report($date, $date2, $mech_id);
					$data['menutitle']='ЦШИ. ТПЛ. Время работы механизма ('.$mech_name.')';
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,60);
				break;

				case 'cshi_tpl_marsh_state':
					$report = new Model_CshiTPLReports();
					$result = $report->cshi_tpl_marsh_state_report($date, $date2, $route_number);
					$data['menutitle']='ЦШИ. ТПЛ. Состояния маршрута №'.$route_number;
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,60);
				break;
				case 'cshi_tpl_refregirator_stop_time':
					$report = new Model_CshiTPLReports();
					$result = $report->cshi_tpl_refregirator_stop_time_report($date, $date2);
					$data['menutitle']='ЦШИ. Время простоя холодильника';
//					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$note=($date2=='')?'<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>':'<p style="font-size:12px;">Отчёт составлен c '.$date.' по '.$date2.' </p>';
				break;
				case 'cshi_tpl_mill':
					$report = new Model_CshiTPLReports();
					$result = $report->cshi_tpl_mill_report();
					$data['menutitle']='ЦШИ. ТПЛ. Время работы мельницы с момента последнего перебора шаров';
					$note='<p style="font-size:12px;">Отчёт составлен '.$date;
					$column_width=array(27,27,27);
				break;
				case 'cshi_press2_alarm':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_pfu_alarm_report($date, $date2);
					$data['menutitle']='ЦШИ. ПФУ. Пресс№2. Архив аварийных сообщений.';
					$note=($date2=='')?'<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>':'<p style="font-size:12px;">Отчёт составлен c '.$date.' по '.$date2.' </p>';
					$column_width=array(18,32.5,16,16);
				break;
				case 'cshi_press5_alarm':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_pfu_press5_alarm_report($date, $date2);
					$data['menutitle']='ЦШИ. ПФУ. Пресс№5. Архив аварийных сообщений.';
					$note=($date2=='')?'<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>':'<p style="font-size:12px;">Отчёт составлен c '.$date.' по '.$date2.' </p>';
					$column_width=array(18,32.5,16,16);
				break;
				case 'cshi_press3_alarm':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_pfu_press3_alarm_report($date, $date2);
					$data['menutitle']='ЦШИ. ПФУ. Пресс№3. Архив аварийных сообщений.';
					$note=($date2=='')?'<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>':'<p style="font-size:12px;">Отчёт составлен c '.$date.' по '.$date2.' </p>';
					$column_width=array(18,32.5,16,16);
				break;
				case 'recipe_archive':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_pfu_press2_recipe_archive($date, $date2);
					$data['menutitle']='ЦШИ. ПФУ. Пресс№2. Архив рецептов.';
					$note=($date2=='')?'<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>':'<p style="font-size:12px;">Отчёт составлен c '.$date.' по '.$date2.' </p>';
					$column_width=array(18,15,15,15,15);
				break;
				case 'line_5_recipe_archive':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_pfu_press5_recipe_archive($date, $date2);
					$data['menutitle']='ЦШИ. ПФУ. Пресс№5. Архив рецептов.';
					$note=($date2=='')?'<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>':'<p style="font-size:12px;">Отчёт составлен c '.$date.' по '.$date2.' </p>';
					$column_width=array(18,15,15,15,15);
				break;
				case 'line_3_recipe_archive':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_pfu_press3_recipe_archive($date, $date2);
					$data['menutitle']='ЦШИ. ПФУ. Пресс№3. Архив рецептов.';
					$note=($date2=='')?'<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>':'<p style="font-size:12px;">Отчёт составлен c '.$date.' по '.$date2.' </p>';
					$column_width=array(18,15,15,15,15);
				break;
				case 'line_2_balancing_archive':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_pfu_press_line_2_balancing_archive($date, $date2,$scale_number, $zone, $deviation); // print_r ($result);return;
					$data['menutitle']='ЦШИ. ПФУ. Пресс№2. Архив дозирования материалов.';
					$note=($date2=='')?'<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>':'<p style="font-size:12px;">Отчёт составлен c '.$date.' по '.$date2.' </p>';
					$column_width=array(8,18);
				break;
				case 'line_5_balancing_archive':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_pfu_press_line_5_balancing_archive($date, $date2,$scale_number, $zone, $deviation); // print_r ($result);return;
					$data['menutitle']='ЦШИ. ПФУ. Пресс№5. Архив замесов.';
					$note=($date2=='')?'<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>':'<p style="font-size:12px;">Отчёт составлен c '.$date.' по '.$date2.' </p>';
					$column_width=array(8,18);
				break;
				case 'line_5_balancing_archive2':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_pfu_press_line_5_balancing_archive2($date, $date2,$scale_number); // print_r ($result);return;
					$data['menutitle']='ЦШИ. ПФУ. Пресс№5. Архив дозирования весов.';
					$note=($date2=='')?'<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>':'<p style="font-size:12px;">Отчёт составлен c '.$date.' по '.$date2.' </p>';
					$column_width=array(8,18);
				break;
				case 'line_3_balancing_archive':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_pfu_press_line_3_balancing_archive($date, $date2,$scale_number, $zone, $deviation); // print_r ($result);return;
					$data['menutitle']='ЦШИ. ПФУ. Пресс№3. Архив замесов.';
					$note=($date2=='')?'<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>':'<p style="font-size:12px;">Отчёт составлен c '.$date.' по '.$date2.' </p>';
					$column_width=array(8,18);
				break;
				case 'line_3_balancing_archive2':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_pfu_press_line_3_balancing_archive2($date, $date2,$scale_number); // print_r ($result);return;
					$data['menutitle']='ЦШИ. ПФУ. Пресс№3. Архив дозирования весов.';
					$note=($date2=='')?'<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>':'<p style="font-size:12px;">Отчёт составлен c '.$date.' по '.$date2.' </p>';
					$column_width=array(8,18);
				break;
				case 'line_6_balancing_archive':
					$report = new Model_Cshipfureports();
//					$result = $report->cshi_pfu_press_line_6_balancing_archive($date, $date2,$scale_number, $zone, $deviation); // print_r ($result);return;
					$result = $report->cshi_pfu_press_line_6_balancing_archive($date, $date2,$scale_number); // print_r ($result);return;
					$data['menutitle']='ЦШИ. ПФУ. Пресс№6. Архив дозирования материалов.';
					$note=($date2=='')?'<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>':'<p style="font-size:12px;">Отчёт составлен c '.$date.' по '.$date2.' </p>';
					$column_width=array(8,18);
				break;
				case 'cshi_tpl_vent_system_working_time':
					$report = new Model_CshiTPLReports();
					$result = $report->cshi_tpl_vent_system_working_time_report($date, $date2);
					$data['menutitle']='ЦШИ. ТПЛ. Время работы вентсистемы ДПО, УОГ и ПШ, ПФУ.';
					$note=($date2=='')?'<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>':'<p style="font-size:12px;">Отчёт составлен c '.$date.' по '.$date2.' </p>';
				break;
				case 'cshi_tpl_rorate_furnace_working_time':
					$report = new Model_CshiTPLReports();
					$result = $report->cshi_tpl_rorate_furnace_working_time_report($date, $date2);
					$data['menutitle']='ЦШИ. ТПЛ. Время работы вращающихся печей в режиме холостого хода.';
					$note=($date2=='')?'<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>':'<p style="font-size:12px;">Отчёт составлен c '.$date.' по '.$date2.' </p>';
				break;
				case 'cshi_tpl_alarm':
					$report = new Model_CshiTPLReports();
					$result = $report->cshi_tpl_alarm_report($date, $date2);
					$data['menutitle']='ЦШИ. ТПЛ. Архив аварийных сообщений.';
					$note=($date2=='')?'<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>':'<p style="font-size:12px;">Отчёт составлен c '.$date.' по '.$date2.' </p>';
					$column_width=array(18,63);
				break;
				case 'cshi_tp1_mech_working_time':
					$report = new Model_Cshitp1reports();
					$result = $report->cshi_tp1_mech_working_time_report($date, $date2, $mech_id);
					$data['menutitle']='ЦШИ. ТП1. Время работы механизма ('.$mech_name.')';
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,63);
				break;
				case 'cshi_pfu_mech_working_time':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_pfu_mech_working_time_report($date, $date2, $mech_id);
					$data['menutitle']='ЦШИ. ПФУ. Время работы механизма ('.$mech_name.')';
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,63);
				break;
				case 'mixer_stakan_current':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_pfu_currents($date, $date2, 185, 30/4096);
					$data['menutitle']='ЦШИ. ПФУ. Ток стакана смесителя';
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,43);
				break;
				case 'mixer_zavih_current':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_pfu_currents($date, $date2, 186, 150/4096);
					$data['menutitle']='ЦШИ. ПФУ. Ток завихрителя смесителя';
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,43);
				break;
				case 'press_current':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_pfu_currents($date, $date2, 227, 150/4096);
					$data['menutitle']='ЦШИ. ПФУ. Ток прессования';
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,43);
				break;
				case 'press6_current':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_pfu_analog($date, $date2, 06);
					$data['menutitle']='ЦШИ. ПФУ. Пресс 6. Ток прессования';
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,43);
				break;
				case 'press7_current':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_pfu_analog($date, $date2, 07);
					$data['menutitle']='ЦШИ. ПФУ. Пресс 7. Ток прессования';
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,43);
				break;
				case 'press3_current':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_pfu_analog($date, $date2, 8);
					$data['menutitle']='ЦШИ. ПФУ. Пресс 3. Ток прессования';
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,43);
				break;
				case 'press9_current':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_pfu_analog($date, $date2, 9);
					$data['menutitle']='ЦШИ. ПФУ. Пресс 9. Ток прессования';
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,43);
				break;
				case 'press4_current':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_pfu_analog($date, $date2, 10);
					$data['menutitle']='ЦШИ. ПФУ. Пресс 4. Ток прессования';
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,43);
				break;
				case 'press8_current':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_pfu_analog($date, $date2, 11);
					$data['menutitle']='ЦШИ. ПФУ. Пресс 8. Ток прессования';
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,43);
				break;
				case 'press10_current':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_pfu_analog($date, $date2, 12);
					$data['menutitle']='ЦШИ. ПФУ. Пресс 10. Ток прессования';
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,43);
				break;
				case 'press11_current':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_pfu_analog($date, $date2, 13);
					$data['menutitle']='ЦШИ. ПФУ. Пресс 11. Ток прессования';
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,43);
				break;
				case 'tp1_teh':
					$report = new Model_Cshitp1reports();
					$result = $report->cshi_tp1_teh($date, $date2, $param_type, $y_title_name[$param_type]);
					$data['menutitle']='ЦШИ. Туннельная печь №1. '.$param_name[$param_type];
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,43);
				break;
				case 'cshi_dry4':
					$report = new Model_Cshitp1reports();
					$result = $report->cshi_tp1_teh($date, $date2, $param_type, $y_title_name[$param_type]);
					$data['menutitle']='ЦШИ. Сушило №4. '.$param_name[$param_type];
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,43);
				break;
				case 'cshi_rotary_furn':
					$report = new Model_CshiRFreports();
					$result = $report->cshi_rotary_furn2($date, $date2, $param_type, $y_title_name[$param_type]);
					$data['menutitle']='ЦШИ. Вращающаяся печь №1. '.$param_name[$param_type];
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,43);
				break;
				case 'cshi_rotary_furn2':
					$report = new Model_CshiRFreports();
					$result = $report->cshi_rotary_furn2($date, $date2, $param_type, $y_title_name[$param_type]);
					$data['menutitle']='ЦШИ. Вращающаяся печь №2. '.$param_name[$param_type];
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,43);
				break;
				case 'cshi_desiccators':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_desiccators($date, $date2, $param_type, $y_title_name[$param_type]);
					$data['menutitle']='ЦШИ. Сушильные барабаны. '.$param_name[$param_type];
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,43);
				break;
				case 'cshi_carts':
					$report = new Model_Cshicartsreports();
					$result = $report->cshi_carts($date, $date2, $param_type, $y_title_name[$param_type]);
					$data['menutitle']='ЦШИ. Вагонные весы. '.$param_name[$param_type];
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,43);
				break;
				case 'cshi_balloon':
					$report = new Model_CshiRFreports();
					$result = $report->cshi_rf_balloon_working_time_report($date, $date2, $param_type);
					$data['menutitle']='ЦШИ. Вращающиеся печи. Время работы механизма ('.$mech_name.')';
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,43);
				break;
				case 'cshi_conveyor_scales_daily':
					$report = new Model_Cshiconscalesreports();
					$result = $report->cshi_vonveyor_scales_daily_report($date, $mech_id);
					$data['menutitle']='ЦШИ. Конвейерные весы. '.$param_name[$mech_id];
					$note='<p style="font-size:12px;">Отчёт составлен на '.$date.' </p>';
					$column_width=array(18,43);
				break;
				case 'cshi_conveyor_scales_monthly':
					$report = new Model_Cshiconscalesreports();
					$result = $report->cshi_vonveyor_scales_monthly_report($date, $date2, $mech_id);
					$data['menutitle']='ЦШИ. Конвейерные весы. '.$param_name[$mech_id];
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,43);
				break;
				case 'cshi_conveyor_scales2_daily':
					$report = new Model_Cshiconscalesreports();
					$result = $report->cshi_vonveyor_scales2_daily_report($date, $mech_id,$brigade);
					$brigade_text = $brigade!=0 ? ". Бригада №" . $brigade : "";
					$data['menutitle']='ЦШИ. Конвейерные весы. '.$param_name[$mech_id] . $brigade_text;
					$note='<p style="font-size:12px;">Отчёт составлен на '.$date.' </p>';
					$column_width=array(18,43);
				break;
				case 'cshi_conveyor_scales2_monthly':
					$report = new Model_Cshiconscalesreports();
					$result = $report->cshi_vonveyor_scales2_monthly_report($date, $date2, $mech_id,$brigade);
					$data['menutitle']='ЦШИ. Конвейерные весы. '.$param_name[$mech_id];
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,43);
				break;
				case 'cshi_line_5_mech_working_time':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_line_5_mech_working_time_report($date, $date2, $mech_id);
					$data['menutitle']='ЦШИ. ПФУ. Линия прессования №5. Время работы механизма ('.$mech_name.')';
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,63);
				break;
				case 'cshi_line_3_mech_working_time':
					$report = new Model_Cshipfureports();
					$result = $report->cshi_line_3_mech_working_time_report($date, $date2, $mech_id);
					$data['menutitle']='ЦШИ. ПФУ. Линия прессования №3. Время работы механизма ('.$mech_name.')';
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,63);
				break;
				case 'cshi_rotary_furn_mech_working_time':
					$report = new Model_cshiRFreports();
					$result = $report->cshi_rotary_furn_mech_working_time($date, $date2, $mech_id);
					$data['menutitle']='ЦШИ. ВП. Время работы механизма ('.$mech_name.')';
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,60);
				break;
				case 'pfu_press_current':
					$report = new Model_Cshipfureports();
					$result = $report->pfu_press_current_report($date, $date2, $press_number, $limit_current);
					$data['menutitle']='ЦШИ. ПФУ. Пресс №'.$press_number.'. Количество превышений тока ('.$limit_current.' А)';
					$note='<p style="font-size:12px;">Отчёт составлен c '.$date.' по ' .$date2. ' </p>';
					$column_width=array(18,63);
				break;
				case 'pfu_press_current_overload':
					$report = new Model_Cshipfureports();
					$result = $report->pfu_press_current_overload($date, $date2);
					$data['menutitle']='ЦШИ. ПФУ. Перегрузы прессов';
					$note=($date2=='')?'<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>':'<p style="font-size:12px;">Отчёт составлен c '.$date.' по '.$date2.' </p>';
					$column_width=array(18,63);
				break;
				case 'rotary_furn_brigade':
					$report = new Model_Cshibrigadereports();
					$result = $report->cshi_rotary_furnace_brigade_report($date, $date2,$brigade,$furnace);
					$data['menutitle']='ЦШИ. Отделение вращающихся печей. Система учета энергоресурсов';
					$note=($date2=='')?'<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>':'<p style="font-size:12px;">Отчёт составлен на период c '.$date.' по '.((strtotime($date2)<strtotime("now"))?$date2:date('d.m.Y')).' </p>';
					$column_width=array(18,63);
				break;
				case 'rotary_furn_brigade2':
					$report = new Model_Cshibrigadereports();
					$result = $report->cshi_rotary_furnace_brigade_report2($date, $date2,$brigade,$furnace);
					$data['menutitle']='ЦШИ. Отделение вращающихся печей. Система учета энергоресурсов';
					$note=($date2=='')?'<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>':'<p style="font-size:12px;">Отчёт составлен на период c '.$date.' по '.((strtotime($date2)<strtotime("now"))?$date2:date('d.m.Y')).' </p>';
					$column_width=array(18,63);
				break;
				default:
					$data['menutitle']='Отчёты ТПЛ ЦШИ';
					$result='';
				break;
			}
			$this->template->title = $data['menutitle'];
			$menu_view = View::factory('menu/main_menu',$data)->render();
			switch ($report_type)
			{
				case 'cshi_tpl_refregirator_stop_time':
					$table = Table::factory($result['data']);
					$table->set_attributes('id', 'report_table');
					$table->set_footer($result['footer']);
					$table->set_column_titles($result['column_titles']);
					$table2 = Table::factory($result['data2']);
					$table2->set_attributes('id', 'report_table');
					$table2->set_footer($result['footer2']);
					$table2->set_column_titles($result['column_titles2']);
					if (count($result) < 2)
					{
						$data=array('error_topic'=>'Ошибка!','error_message'=>'Нет данных за указанный период времени!');
						$error_message_view = View::factory('/error_message',$data)->render();
						$this->template->content=$error_message_view;
					}
					else if ($media=="xls")
					{
						$ws = new Spreadsheet(array(
							'author' => 'Kohana-PHPExcel',
							'title' => 'Report',
							'subject' => 'Subject',
							'description' => 'Description',
						));
						$ws->set_response($this->response);
						$result['reportdate'] = $note;
						$result['caption'] = $data['menutitle'].". Холодильник 071";
						$ws->set_report_data($result,"Холодильник 071",array(18.1,40));
						$result['caption'] = $data['menutitle'].". Холодильник 072";
						$result['data']=$result['data2'];
						$result['footer']=$result['footer2'];
						$result['column_titles']=$result['column_titles2'];
						$ws->set_report_data($result,"Холодильник 072",array(18.1,40));
						$ws->send(array('name'=>'report', 'format'=>'Excel5'));
					}
					else
					{
						$table1_content = '<p style="font-size:12px;">&nbsp;</p><center> Холодильник 071 </center><br>'.$table->render();
						$table2_content = '<p style="font-size:12px;">&nbsp;</p><center> Холодильник 072 </center><br>'.$table2->render();
						$data['report_content']='<table><tr><td colspan=2 valign=top align=center>'.$note.'</td></tr><tr><td valign=top>'.$table1_content.'</td><td valign=top>'.$table2_content.'</td></tr></table>';
						$this->template->content = View::factory('report_result_view',$data)->render();
					}
				break;
				case 'cshi_tpl_rorate_furnace_working_time':
					$table1 = Table::factory($result['data1']);
					$table1->set_attributes('id', 'report_table');
					$table1->set_footer($result['footer1']);
					$table1->set_column_titles($result['column_titles']);
					$table2 = Table::factory($result['data2']);
					$table2->set_attributes('id', 'report_table');
					$table2->set_footer($result['footer2']);
					$table2->set_column_titles($result['column_titles']);
					if (count($result) < 2)
					{
						$data=array('error_topic'=>'Ошибка!','error_message'=>'Нет данных за указанный период времени!');
						$error_message_view = View::factory('/error_message',$data)->render();
						$this->template->content=$error_message_view;
					}
					else if ($media=="xls")
					{
						$ws = new Spreadsheet(array(
							'author' => 'Kohana-PHPExcel',
							'title' => 'Report',
							'subject' => 'Subject',
							'description' => 'Description',
						));
						$ws->set_response($this->response);
						$result['reportdate'] = $note;
						$result['caption'] = $data['menutitle']." Печь №1";
						$result['data']=$result['data1'];
						$result['footer']=$result['footer1'];
						$ws->set_report_data($result,"Печь №1",array(41,41));
						$result['caption'] = $data['menutitle']." Печь №2";
						$result['data']=$result['data2'];
						$result['footer']=$result['footer2'];
						$ws->set_report_data($result,"Печь №2",array(41,41));
						$ws->send(array('name'=>'report', 'format'=>'Excel5'));
					}
					else
					{
						$table1_content = '<p style="font-size:12px;">&nbsp;</p><center> Печь №1 (073) </center><br>'.$table1->render();
						$table2_content = '<p style="font-size:12px;">&nbsp;</p><center> Печь №2 (074) </center><br>'.$table2->render();
						$data['report_content']='<table><tr><td colspan=2 valign=top align=center>'.$note.'</td></tr><tr><td valign=top>'.$table1_content.'</td><td valign=top>'.$table2_content.'</td></tr></table>';
						$this->template->content = View::factory('report_result_view',$data)->render();
					}
				break;
				case 'line_2_balancing_archive':
				case 'line_5_balancing_archive':
				case 'line_3_balancing_archive':
					$table_content='';

					/*foreach ($result as $recipe_ind=>$recipe)
					{
						$table_index++;
						$table = Table::factory($recipe['data']);
						$table->set_attributes('id', 'report_table');
						$table->set_caption($recipe['caption']);
						$table->set_footer($recipe['footer']);
						$table->set_column_titles($recipe['column_titles']);
						if ($table_index==count($result) or $table_index==1)
							$table_content .= $table->render().'<BR>';
						else if (count($result)>3
					}*/
					//print_r($result);
					if (count($result)==0)
					{
						$data=array('error_topic'=>'Ошибка!','error_message'=>'Нет данных за указанный период времени!');
						$error_message_view = View::factory('/error_message',$data)->render();
						$this->template->content=$error_message_view;
					}
					else if ($media=="xls")
					{
						$ws = new Spreadsheet(array(
							'author' => 'Kohana-PHPExcel',
							'title' => 'Report',
							'subject' => 'Subject',
							'description' => 'Description',
						));
						$ws->set_response($this->response);
						$xls_report['caption'] = $data['menutitle'];
						$xls_report['reportdate'] = $note;
						$xls_report['data']=array();
						$xls_report['footer']="";
						$xls_report['column_titles']=$result[count($result)-1]['column_titles'];
						$xls_report['data']=$result[count($result)-1]['data'];
						$ws->set_report_data($xls_report,"Отчёт",$column_width);
						$ws->send(array('name'=>'report', 'format'=>'Excel5'));
					}
					else
					{
						$table = Table::factory($result[0]['data']);
						$table->set_attributes('id', 'report_table');
						$table->set_caption($result[0]['caption']);
						$table->set_footer($result[0]['footer']);
						$table->set_column_titles($result[0]['column_titles']);
						$table_content = $table->render().'<BR>';
						if (count($result)>1)
						{
							$table_content .= '<table><tr valign="top">';
							for ($i=1;$i<count($result)-1;$i++)
							{
								$table = Table::factory($result[$i]['data']);
								$table->set_attributes('id', 'report_table');
								$table->set_caption($result[$i]['caption']);
								$table->set_footer($result[$i]['footer']);
								$table->set_column_titles($result[$i]['column_titles']);
								$table_content .='<td>'.$table->render().'</td>';
							}
							$table_content .= '</tr></table><BR>';
							$table = Table::factory($result[$i]['data']);
							$table->set_attributes('id', 'report_table');
							$table->set_caption($result[$i]['caption']);
							$table->set_footer($result[$i]['footer']);
							$table->set_column_titles($result[$i]['column_titles']);
							$table_content .= $table->render();
						}
						$data['report_content']=$table_content;
						$this->template->content = View::factory('report_result_view',$data)->render();
					}
				break;
				case 'line_6_balancing_archive':
					$table_content='';
//					print_r(count($result));
					if (count($result)<=1)
					{
						$data=array('error_topic'=>'Ошибка!','error_message'=>'Нет данных за указанный период времени!');
						$error_message_view = View::factory('/error_message',$data)->render();
						$this->template->content=$error_message_view;
					}
					else if ($media=="xls")
					{
						$ws = new Spreadsheet(array(
							'author' => 'Kohana-PHPExcel',
							'title' => 'Report',
							'subject' => 'Subject',
							'description' => 'Description',
						));
						$ws->set_response($this->response);
						$xls_report['caption'] = $data['menutitle'];
						$xls_report['reportdate'] = $note;
						$xls_report['data']=array();
//						$xls_report['footer']="";
						$xls_report['footer']=$result[0]['footer'];
						$xls_report['column_titles']=$result[count($result)-1]['column_titles'];
						$xls_report['data']=$result[count($result)-1]['data'];
						$ws->set_report_data($xls_report,"Отчёт",$column_width);
						$ws->send(array('name'=>'report', 'format'=>'Excel5'));
					}
					else
					{
					//таблица отклонений
/*						$table = Table::factory($result[0]['data']);
						$table->set_attributes('id', 'report_table');
						$table->set_caption($result[0]['caption']);
						$table->set_footer($result[0]['footer']);
						$table->set_column_titles($result[0]['column_titles']);
						$table_content = $table->render().'<BR>';*/
						if (count($result)>1)
						{
//							$table_content .= '<table><tr valign="top">';
							for ($i=1;$i<count($result)-1;$i++)
							{
/*								$table = Table::factory($result[$i]['data']);
								$table->set_attributes('id', 'report_table');
								$table->set_caption($result[$i]['caption']);
								$table->set_footer($result[$i]['footer']);
								$table->set_column_titles($result[$i]['column_titles']);
								$table_content .='<td>'.$table->render().'</td>';*/
							}
//							$table_content .= '</tr></table><BR>';
							$table = Table::factory($result[$i]['data']);
							$table->set_attributes('id', 'report_table');
							$table->set_caption($result[$i]['caption']);
							$table->set_footer($result[0]['footer']);
							$table->set_column_titles($result[$i]['column_titles']);
							$table_content .= $table->render();
						}
						$data['report_content']=$table_content;
						$this->template->content = View::factory('report_result_view',$data)->render();
					}
				break;
				case 'recipe_archive':
				case 'line_5_recipe_archive':
				case 'line_3_recipe_archive':
					$table_content='';
					foreach ($result as $recipe_ind=>$recipe)
					{
						$table = Table::factory($recipe['data']);
						$table->set_attributes('id', 'report_table');
						$table->set_caption($recipe['caption']);
						$table->set_footer($recipe['footer']);
						$table->set_column_titles($recipe['column_titles']);
						$table_content = $table_content.$table->render().'<BR>';
						//$this->template->content=$this->template->content.$table1_content;
					}
					if (count($result)==0)
					{
						$data=array('error_topic'=>'Ошибка!','error_message'=>'Нет данных за указанный период времени!');
						$error_message_view = View::factory('/error_message',$data)->render();
						$this->template->content=$error_message_view;
					}
					else if ($media=="xls")
					{
						$ws = new Spreadsheet(array(
							'author' => 'Kohana-PHPExcel',
							'title' => 'Report',
							'subject' => 'Subject',
							'description' => 'Description',
						));
						$ws->set_response($this->response);
						$xls_report['caption'] = $data['menutitle'];
						$xls_report['reportdate'] = $note;
						$xls_report['data']=array();
						$xls_report['footer']="";
						$xls_report['column_titles']=$result[0]['column_titles'];
						foreach ($result as $recipe_ind=>$recipe)
						{
							$xls_report['data']=array_merge($xls_report['data'],$recipe['data']);
						}
						$ws->set_report_data($xls_report,"Отчёт",$column_width);
						$ws->send(array('name'=>'report', 'format'=>'Excel5'));
					}
					else
					{
						$data['report_content']=$table_content;
						$this->template->content = View::factory('report_result_view',$data)->render();
					}
  				break;
				case 'rotary_furn_brigade':
				case 'rotary_furn_brigade2':
					$table_content='';
					foreach ($result as $recipe_ind=>$recipe)
					{
						$table = Table::factory($recipe['data']);
						$table->set_attributes('id', 'report_table');
						$table->set_caption($recipe['caption']);
						$table->set_footer($recipe['footer']);
						$table->set_column_titles($recipe['column_titles']);
						$table_content = $table_content.$table->render().'<BR>';
					}
					foreach ($result[0]['data'] as $key=>$record)
					{
						$value_a[$record[1]]=$record[2];
					}
					if (isset($result[1]['data'])) {
						foreach ($result[1]['data'] as $key=>$record)
						{
							$value_b[$record[1]]=$record[2];
						}
					}
					if (count($result)==0)
					{
						$data=array('error_topic'=>'Ошибка!','error_message'=>'Нет данных за указанный период времени!');
						$error_message_view = View::factory('/error_message',$data)->render();
						$this->template->content=$error_message_view;
					}
					else if ($media=="xls")
					{
						$ws = new Spreadsheet(array(
							'author' => 'Kohana-PHPExcel',
							'title' => 'Report',
							'subject' => 'Subject',
							'description' => 'Description',
						));
						$ws->set_response($this->response);
						$xls_report['caption'] = $data['menutitle'];
						$xls_report['reportdate'] = $note;
						$xls_report['data']=array();
						$xls_report['footer']="";
						$xls_report['column_titles']=$result[0]['column_titles'];
						foreach ($result as $recipe_ind=>$recipe)
						{
							$xls_report['data']=array_merge($xls_report['data'],$recipe['data']);
						}
						$ws->set_report_data($xls_report,"Отчёт",$column_width);
						$ws->send(array('name'=>'report', 'format'=>'Excel5'));
					}
					else if ($report_type==rotary_furn_brigade2)
					{
						$data['report_content']=$note.'<BR>'.$table_content;
						$data['img']="/ASUTP/php/brigade_img.php?a=".urlencode(join("&",array($value_a[1],$value_a[2],$value_a[3],$value_a[4],$value_b[1],$value_b[2],$value_b[3],$value_b[4],)));
						$this->template->content = View::factory('brigade_report_result_view2',$data)->render();
					}
					else
					{
						$data['report_content']=$note.'<BR>'.$table_content;
						$data['note']=$note;
						$this->template->content = View::factory('brigade_report_result_view',$data)->render();
					}
  				break;
				case 'cshi_tpl_vent_system_working_time':
					$caption = array('<p id=caption>Учет работы пылегазоулавливающей установки ДПО</p>',
									'<p id=caption>Учет работы пылегазоулавливающей установки УОГ и ПШ</p>',
									'<p id=caption>Учет работы пылегазоулавливающей установки ПФУ</p>');

					if ($result > 0) {
						foreach ($result['data'] as $array_ind=>$array)
						{
							$table = Table::factory($array);
							$table->set_column_titles($result['column_titles']);
							$table->set_attributes('id', 'report_table_vent');
							$table->set_footer($result['footer']);
							if ($array_ind == 1)
								$table1_content = $note.$caption[0].$table->render();
							if ($array_ind == 2)
								$table2_content = $caption[1].$table->render();
							if ($array_ind == 3)
								$table3_content = $caption[2].$table->render();
						}
						//$this->template->content = '<table id=Inner_Table><tr><td valign=top>'.$table1_content.'</td></tr><tr><td valign=top>'.$table2_content.'</td></tr><tr><td valign=top>'.$table3_content.'</td></tr></table>';
						$this->template->content = $table1_content.'<BR>'.$table2_content.'<BR>'.$table3_content;
					}
					if (count($result['data'])==0)
					{
						$data=array('error_topic'=>'Ошибка!','error_message'=>'Нет данных за указанный период времени!');
						$error_message_view = View::factory('/error_message',$data)->render();
						$this->template->content=$error_message_view;
					}
					else if ($media=="xls")
					{
						//print_r($result);exit;
						$ws = new Spreadsheet(array(
							'author' => 'Kohana-PHPExcel',
							'title' => 'Report',
							'subject' => 'Subject',
							'description' => 'Description',
						));
						$ws->set_response($this->response);
						$xls_report['reportdate'] = $note;
						$xls_report['footer']="";
						$xls_report['column_titles']=$result['column_titles'];
						$xls_report['data']=$result['data'][1];
						$xls_report['caption'] = $caption[0];
						$ws->set_report_data($xls_report,"ДПО",array(3.5,24,24,24));
						$xls_report['data']=$result['data'][2];
						$xls_report['caption'] = $caption[1];
						$ws->set_report_data($xls_report,"УОГ и ПШ",array(3.5,24,24,24));
						$xls_report['data']=$result['data'][3];
						$xls_report['caption'] = $caption[2];
						$ws->set_report_data($xls_report,"ПФУ",array(3.5,24,24,24));
						$ws->send(array('name'=>'report', 'format'=>'Excel5'));
					}
					else
					{
						//$data['report_content']='<table id=Inner_Table><tr><td valign=top>'.$table1_content.'</td></tr><tr><td valign=top>'.$table2_content.'</td></tr><tr><td valign=top>'.$table3_content.'</td></tr></table>';
						$data['report_content']=$table1_content.'<BR>'.$table2_content.'<BR>'.$table3_content;
						$this->template->content = View::factory('report_result_view',$data)->render();
					}
				break;
//				case 'cshi_press5_alarm':
				default:
					if (count($result['data'])==0)
					{
						$data=array('error_topic'=>'Ошибка!','error_message'=>'Нет данных за указанный период времени!');
						$error_message_view = View::factory('/error_message',$data)->render();
						$this->template->content=$error_message_view;
					}
					else if ($media=="xls")
					{
						$ws = new Spreadsheet(array(
							'author' => 'Kohana-PHPExcel',
							'title' => 'Report',
							'subject' => 'Subject',
							'description' => 'Description',
						));
						$ws->set_response($this->response);
						$result['caption'] = $data['menutitle'];
						$result['reportdate'] = $note;
						$ws->set_report_data($result,"Отчёт",$column_width);
						$ws->send(array('name'=>'report', 'format'=>'Excel5'));
					}
					else
					{
						$table = Table::factory($result['data']);
						$table->set_footer($result['footer']);
						$table->set_attributes('id', 'report_table');
						$table->set_column_titles($result['column_titles']);
						$table1_content = $table->render();
						$data['report_content']=$note.$table1_content;
						$this->template->content = View::factory('report_result_view',$data)->render();
					}
				break;
			}
			$this->template->menu = $menu_view;
			$local_menu_view = View::factory('menu/cshi_reports_menu')->render();
			$this->template->local_menu = $local_menu_view;
		}
//------------------------------------------------------------------------------
		public function action_excel()
		{
/*			$ws = new Spreadsheet(array(
			    'author' => 'Kohana-PHPExcel',
			    'title' => 'Report',
			    'subject' => 'Subject',
			    'description' => 'Description',
			));

			$ws->set_active_sheet( 0);
			$ws->set_response($this->response);
			$as = $ws->get_active_sheet();
			$as->setTitle('Report');
			$as->getDefaultStyle()->getFont()->setSize(9);
			$as->getColumnDimension('A')->setWidth(7);
			$as->getColumnDimension('B')->setWidth(40);
			$as->getColumnDimension('C')->setWidth(12);
			$as->getColumnDimension('D')->setWidth(10);
			$report = new Model_Cshipfureports();
			$date='11-10-04 00:00:00';
			$date2='11-10-05 23:00:00';
			$result = $report->cshi_pfu_alarm_report($date, $date2);
			$result['caption'] = 'ЦШИ. ПФУ. Пресс№2. Архив аварийных сообщений.';
			$data['menutitle']='ЦШИ. ПФУ. Пресс№2. Архив аварийных сообщений.';
			$note=($date2=='')?'<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>':'<p style="font-size:12px;">Отчёт составлен c '.$date.' по '.$date2.' </p>';

//			$sh = ;

//			$ws->set_data($sh, false);
//			$ws->set_data($result['data'], false);
			$ws->set_report_data($result,"Отчёт",array(0,0));
			$ws->send(array('name'=>'report', 'format'=>'Excel5'));*/
//      print_r($result['data']);
		}
//------------------------------------------------------------------------------
		public function action_askureports()
		{
			$styles = array('css/calendar/aqua/theme.css'=>'screen');
			$scripts = array('js/calendar/calendar-setup.js','js/calendar/lang/calendar-ru.js','js/calendar/calendar.js','js/calendar/checkDate.js');
			$this->template->styles = $styles;
			$this->template->scripts = $scripts;
			//Названия отчётов
			$title = array (	"cshi_oxygen"=>"Кислород ЦШИ. Отчёты АСКУ ТЭР",
								"cshi_rotating_oven_1_coke_gas"=>"Коксовый газ вращ. печи №1 ЦШИ. Отчёты АСКУ ТЭР",
								"cshi_rotating_oven_2_coke_gas"=>"Коксовый газ вращ. печи №2 ЦШИ. Отчёты АСКУ ТЭР",
								"cshi_rotary_drier1_coke_gas"=>"Коксовый газ суш. барабана №1 ЦШИ. Отчёты АСКУ ТЭР",
								"cshi_rotary_drier2_coke_gas"=>"Коксовый газ суш. барабана №2 ЦШИ. Отчёты АСКУ ТЭР",
								"cshi_rotary_drier3_coke_gas"=>"Коксовый газ суш. барабана №3 ЦШИ. Отчёты АСКУ ТЭР",
								"csi_rotary_driers_coke_gas_DPU"=>"Коксовый газ суш. барабаны ДПУ ЦСИ. Отчёты АСКУ ТЭР",
								"csi_rotary_driers_coke_gas_DPandFU"=>"Коксовый газ суш. барабаны ДП и ФУ ЦСИ. Отчёты АСКУ ТЭР",
								"cshi_tunnel_furnaces_coke_gas"=>"Коксовый газ тун. печи ЦШИ. Отчёты АСКУ ТЭР",
								"csi_forge_furnaces_coke_gas"=>"Коксовый газ кузнеч. печи ЦСИ. Отчёты АСКУ ТЭР",
								"csi_coke_gas_heating_fu_1_2"=>"Коксовый газ нагр. печи №1,2 терм. уч. ЦСИ. Отчёты АСКУ ТЭР",
								"cshi_natural_gas"=>"Природный газ ЦШИ выс. сторона. Отчёты АСКУ ТЭР",
								"csi_natural_gas"=>"Природный газ ЦCИ. Отчёты АСКУ ТЭР",
								"cmdo_natural_gas"=>"Природный газ ЦМДО. Отчёты АСКУ ТЭР",
								"cshi_compressed_air"=>"Сжатый воздух ЦШИ. Отчёты АСКУ ТЭР",
								"cshi_compressed_air_gas_cleaning"=>"Сжатый воздух ЦШИ (Газоочистка). Отчёты АСКУ ТЭР",
								"cshi_thermalclamping_water"=>"Теплофикационная вода ЦШИ. Отчёты АСКУ ТЭР",
								"csi_oxygen"=>"Кислород ЦCИ. Отчёты АСКУ ТЭР",
								"csi_steam"=>"Пар. Отчёты АСКУ ТЭР",
								"csi_steam_teh"=>"Пар ЦCИ - технология. Отчёты АСКУ ТЭР",
								"csi_compressed_air"=>"Сжатый воздух ЦСИ. Отчёты АСКУ ТЭР",
								"csi_thermalclamping_water"=>"Теплофикационная вода ЦСИ. Отчёты АСКУ ТЭР",
								"total_oxygen"=>"Расход кислорода. Отчёты АСКУ ТЭР",
								"cshi_analysis1"=>"Анализ энергоресурсов ЦШИ",
								"electro"=>"Электроэнергия. Отчеты АСКУ ТЭР",
								"cshi_compressed_air_formovka"=>"Сжатый воздух формовка ЦШИ. Отчеты АСКУ ТЭР",
								"drinking_water_1"=>"Пожарно-питьевая вода. Быт. ввод №2 (бойлер) ЦШИ",
								"drinking_water_2"=>"Пожарно-питьевая вода. Быт. ввод №1 (зап. вых.) ЦШИ",
								"drinking_water_3"=>"Пожарно-питьевая вода. Маст. энергослужбы ЦШИ",
								"drinking_water_4"=>"Пожарно-питьевая вода. ЦШИ АБК столовая",
								"drinking_water_5"=>"Пожарно-питьевая вода. Бытовые ЦСИ",
								"drinking_water_6"=>"Пожарно-питьевая вода. Мех. мастерская",
								"default"=>"Отчёты АСКУ ТЭР");
			//Рендеринг страницы
			$params = array();
			$tmp_params = explode('&', $this->request->param('id','default'));
			foreach($tmp_params as $key => $value)
			{
				$tmp_param = explode('=', $value);
				$params[$tmp_param[0]] = $tmp_param[1];
			}
			$id = (isset($params['id']))?$params['id']:'default';
			$data['id'] = $id;
			$data['menutitle'] = $title[$id];
			if ($id=='cshi_analysis1')
				$content_view = View::factory('asku/reports/inquery_form_analysis1',$data)->render();
			elseif ($id=='cshi_rotating_oven_1_coke_gas' || $id=='cshi_rotating_oven_2_coke_gas' || $id=='cshi_compressed_air_gas_cleaning')
				$content_view = View::factory('asku/reports/inquery_form_brigade',$data)->render();
			elseif ($id<>'default')
				$content_view = View::factory('asku/reports/inquery_form',$data)->render();
			else $content_view='';
			$this->template->title = $data['menutitle'];
			$menu_view = View::factory('menu/main_menu',$data)->render();
			$this->template->menu = $menu_view;
			$local_menu_view = View::factory('menu/asku_reports_menu')->render();
			$this->template->local_menu = $local_menu_view;
			$this->template->content = $content_view;
		}
//------------------------------------------------------------------------------
		public function action_askureports_result()
		{
			$styles = array('/css/table.css'=>'screen',
                			'/css/print.css'=>'print');
			$this->template->styles = $styles;
			$params = array();
			$tmp_params = explode('&', $this->request->param('id','default'));
			foreach($tmp_params as $key => $value)
			{
				$tmp_param = explode('=', $value);
				$params[$tmp_param[0]] = $tmp_param[1];
			}
			$report_type = (isset($params['report_type']))?$params['report_type']:'cshi_oxygen_daily';
			$date = (isset($params['date1']))?$params['date1']:date('d-m-Y');
			$date2 = (isset($params['date2']))?$params['date2']:date('d-m-Y');
			$brigade=(isset($params['brigade']))?$params['brigade']:0;
			$media = (isset($params['media']))?$params['media']:'html';
			$date = str_replace('-','.',$date);
			$date2 = str_replace('-','.',$date2);
			switch (substr($report_type,strrpos($report_type,"_")+1)) {
				case 'monthly':
					$data['menutitle']=" Месячный отчёт. Отчёты АСКУ ТЭР";
				break;
				case 'daily':
					$data['menutitle']=" Суточный отчёт. Отчёты АСКУ ТЭР";
				break;
				case 'user':
					$data['menutitle']=" Пользовательский отчёт. Отчёты АСКУ ТЭР";
				break;
				case 'brigade':
					$data['menutitle']=" Бригадный отчёт. Отчёты АСКУ ТЭР";
				break;
			}
			$report_type=str_replace("user","monthly",$report_type);
			switch ($report_type)
			{
			//Суточные отчеты
			case 'cshi_oxygen_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->cshi_oxygen_daily_report($date);
				$data['menutitle']='Кислород ЦШИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'cshi_rotating_oven_1_coke_gas_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->cshi_rotating_oven_1_coke_gas_daily_report($date);
				$data['menutitle']='Коксовый газ вращ. печи №1 ЦШИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'cshi_rotating_oven_2_coke_gas_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->cshi_rotating_oven_2_coke_gas_daily_report($date);
				$data['menutitle']='Коксовый газ вращ. печи №2 ЦШИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'cshi_rotary_drier1_coke_gas_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->cshi_rotary_drier1_coke_gas_daily_report($date);
				$data['menutitle']='Коксовый газ суш. барабана №1 ЦШИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'cshi_rotary_drier2_coke_gas_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->cshi_rotary_drier2_coke_gas_daily_report($date);
				$data['menutitle']='Коксовый газ суш. барабана №2 ЦШИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'cshi_rotary_drier3_coke_gas_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->cshi_rotary_drier3_coke_gas_daily_report($date);
				$data['menutitle']='Коксовый газ суш. барабана №3 ЦШИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'csi_rotary_driers_coke_gas_DPU_daily':
				$report = new Model_Csidailyreports();
				$result = $report->csi_rotary_driers_coke_gas_DPU_daily_report($date);
				$data['menutitle']='Коксовый газ суш. барабаны ДПУ ЦСИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'csi_rotary_driers_coke_gas_DPandFU_daily':
				$report = new Model_Csidailyreports();
				$result = $report->csi_rotary_driers_coke_gas_DPandFU_daily_report($date);
				$data['menutitle']='Коксовый газ суш. барабаны ДП и ФУ ЦСИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'cshi_tunnel_furnaces_coke_gas_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->cshi_tunnel_furnaces_coke_gas_daily_report($date);
				$data['menutitle']='Коксовый газ тун. печи ЦШИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'csi_forge_furnaces_coke_gas_daily':
				$report = new Model_Csidailyreports();
				$result = $report->csi_forge_furnaces_coke_gas_daily_report($date);
				$data['menutitle']='Коксовый газ кузнеч. печи ЦCИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'csi_coke_gas_heating_fu_1_2_daily':
				$report = new Model_Csidailyreports();
				$result = $report->csi_coke_gas_heating_fu_1_2_daily_report($date);
				$data['menutitle']='Коксовый газ цементационные печи №1,2 ЦСИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'cshi_natural_gas_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->cshi_natural_gas_daily_report($date);
				$data['menutitle']='Природный газ ЦШИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'csi_natural_gas_daily':
				$report = new Model_Csidailyreports();
				$result = $report->csi_natural_gas_daily_report($date);
				$data['menutitle']='Природный газ ЦCИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'cmdo_natural_gas_daily':
				$report = new Model_Cmdodailyreports();
				$result = $report->cmdo_natural_gas_daily_report($date);
				$data['menutitle']='Природный газ ЦМДО.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'cshi_compressed_air_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->cshi_compressed_air_daily_report($date);
				$data['menutitle']='Сжатый воздух ЦШИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'cshi_compressed_air_gas_cleaning_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->cshi_compressed_air_gas_cleaning_daily_report($date);
				$data['menutitle']='Сжатый воздух ЦШИ (Газоочистка).'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'cshi_compressed_air_formovka_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->cshi_compressed_air_formovka_daily_report($date);
				$data['menutitle']='Сжатый воздух формовка ЦШИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'cshi_thermalclamping_water_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->cshi_thermalclamping_water_daily_report($date);
				$data['menutitle']='Теплофикационная вода ЦШИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'electro_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->electro_daily_report($date);
				$data['menutitle']='Электроэнергия.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
				break;
			case 'drinking_water_1_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->drinking_water_1_daily_report($date);
				$data['menutitle']='Пожарно-питьевая вода. Быт. ввод №2 (бойлер) ЦШИ. '.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
				break;
			case 'drinking_water_2_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->drinking_water_2_daily_report($date);
				$data['menutitle']='Пожарно-питьевая вода. Быт. ввод №1 (зап. вых.) ЦШИ. '.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
				break;
			case 'drinking_water_3_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->drinking_water_3_daily_report($date);
				$data['menutitle']='Пожарно-питьевая вода. Маст. энергослужбы ЦШИ. '.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
				break;
			case 'drinking_water_4_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->drinking_water_4_daily_report($date);
				$data['menutitle']='Пожарно-питьевая вода. Пожарно-питьевая вода. ЦШИ АБК столовая. '.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
				break;
			case 'drinking_water_5_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->drinking_water_5_daily_report($date);
				$data['menutitle']='Пожарно-питьевая вода. Пожарно-питьевая вода. Бытовые ЦСИ. '.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
				break;
			case 'drinking_water_6_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->drinking_water_6_daily_report($date);
				$data['menutitle']='Пожарно-питьевая вода. Мех. мастерская. '.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
				break;
			//Месячные отчёты;
			case 'cshi_oxygen_monthly':
				$report = new Model_Cshimonthlyreports();
				$result = $report->cshi_oxygen_monthly_report($date,$date2);
				$data['menutitle']='Кислород ЦШИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'cshi_rotating_oven_1_coke_gas_monthly':
				$report = new Model_Cshimonthlyreports();
				$result = $report->cshi_rotating_oven_1_coke_gas_monthly_report($date,$date2);
				$data['menutitle']='Коксовый газ вращ. печи №1 ЦШИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'cshi_rotating_oven_2_coke_gas_monthly':
				$report = new Model_Cshimonthlyreports();
				$result = $report->cshi_rotating_oven_2_coke_gas_monthly_report($date,$date2);
				$data['menutitle']='Коксовый газ вращ. печи №2 ЦШИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'cshi_tunnel_furnaces_coke_gas_monthly':
				$report = new Model_Cshimonthlyreports();
				$result = $report->cshi_tunnel_furnaces_coke_gas_monthly_report($date,$date2);
				$data['menutitle']='Коксовый газ тун. печи ЦШИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'cshi_rotary_drier1_coke_gas_monthly':
				$report = new Model_Cshimonthlyreports();
				$result = $report->cshi_rotary_drier1_coke_gas_monthly_report($date,$date2);
				$data['menutitle']='Коксовый газ суш. барабана №1 ЦШИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'cshi_rotary_drier2_coke_gas_monthly':
				$report = new Model_Cshimonthlyreports();
				$result = $report->cshi_rotary_drier2_coke_gas_monthly_report($date,$date2);
				$data['menutitle']='Коксовый газ суш. барабана №2 ЦШИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'cshi_rotary_drier3_coke_gas_monthly':
				$report = new Model_Cshimonthlyreports();
				$result = $report->cshi_rotary_drier3_coke_gas_monthly_report($date,$date2);
				$data['menutitle']='Коксовый газ суш. барабана №3 ЦШИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'csi_rotary_driers_coke_gas_DPU_monthly':
				$report = new Model_Csimonthlyreports();
				$result = $report->csi_rotary_driers_coke_gas_DPU_monthly_report($date,$date2);
				$data['menutitle']='Коксовый газ суш. барабаны ДПУ ЦСИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">График составлен на период с '.$date.' по '.$date2.'</p>';
	  		break;
			case 'csi_rotary_driers_coke_gas_DPandFU_monthly':
				$report = new Model_Csimonthlyreports();
				$result = $report->csi_rotary_driers_coke_gas_DPandFU_monthly_report($date,$date2);
				$data['menutitle']='Коксовый газ суш. барабаны ЦСИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'cshi_natural_gas_monthly':
				$report = new Model_Cshimonthlyreports();
				$result = $report->cshi_natural_gas_monthly_report($date,$date2);
				$data['menutitle']='Природный газ ЦШИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'csi_natural_gas_monthly':
				$report = new Model_Csimonthlyreports();
				$result = $report->csi_natural_gas_monthly_report($date,$date2);
				$data['menutitle']='Природный газ ЦСИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'cmdo_natural_gas_monthly':
				$report = new Model_Cmdomonthlyreports();
				$result = $report->cmdo_natural_gas_monthly_report($date,$date2);
				$data['menutitle']='Природный газ ЦМДО.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'cshi_compressed_air_gas_cleaning_monthly':
				$report = new Model_Cshimonthlyreports();
				$result = $report->cshi_compressed_air_gas_cleaning_monthly_report($date,$date2);
				$data['menutitle']='Сжатый воздух (Газоочистка) ЦШИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'cshi_compressed_air_formovka_monthly':
				$report = new Model_Cshimonthlyreports();
				$result = $report->cshi_compressed_air_formovka_monthly_report($date,$date2);
				$data['menutitle']='Сжатый воздух формовка ЦШИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'cshi_compressed_air_monthly':
				$report = new Model_Cshimonthlyreports();
				$result = $report->cshi_compressed_air_monthly_report($date,$date2);
				$data['menutitle']='Сжатый воздух ЦШИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'cshi_thermalclamping_water_monthly':
				$report = new Model_Cshimonthlyreports();
				$result = $report->cshi_thermalclamping_water_monthly_report($date,$date2);
				$data['menutitle']='Теплофикационная вода ЦШИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'csi_forge_furnaces_coke_gas_monthly':
				$report = new Model_Csimonthlyreports();
				$result = $report->csi_forge_furnaces_coke_gas_monthly_report($date,$date2);
				$data['menutitle']='Коксовый газ кузнеч. печи ЦCИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'csi_coke_gas_heating_fu_1_2_monthly':
				$report = new Model_Csimonthlyreports();
				$result = $report->csi_coke_gas_heating_fu_1_2_monthly_report($date,$date2);
				$data['menutitle']='Коксовый газ цементационные печи №1,2 ЦСИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'csi_oxygen_daily':
				$report = new Model_Csidailyreports();
				$result = $report->csi_oxygen_daily_report($date);
				$data['menutitle']='Кислород ЦCИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'csi_steam_daily':
				$report = new Model_Csidailyreports();
				$result = $report->csi_steam_daily_report($date);
				$data['menutitle']='Пар.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'csi_steam_teh_daily':
				$report = new Model_Csidailyreports();
				$result = $report->csi_steam_teh_daily_report($date);
				$data['menutitle']='Пар ЦCИ - технология.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'csi_compressed_air_daily':
				$report = new Model_Csidailyreports();
				$result = $report->csi_compressed_air_daily_report($date);
				$data['menutitle']='Сжатый воздух ЦСИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'csi_thermalclamping_water_daily':
				$report = new Model_Csidailyreports();
				$result = $report->csi_thermalclamping_water_daily_report($date);
				$data['menutitle']='Теплофикационная вода ЦСИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			//
			case 'csi_oxygen_monthly':
				$report = new Model_Csimonthlyreports();
				$result = $report->csi_oxygen_monthly_report($date,$date2);
				$data['menutitle']='Кислород ЦСИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'csi_steam_monthly':
				$report = new Model_Csimonthlyreports();
				$result = $report->csi_steam_monthly_report($date,$date2);
				$data['menutitle']='Пар.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'csi_steam_teh_monthly':
				$report = new Model_Csimonthlyreports();
				$result = $report->csi_steam_teh_monthly_report($date,$date2);
				$data['menutitle']='Пар ЦСИ - технология.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'csi_compressed_air_monthly':
				$report = new Model_Csimonthlyreports();
				$result = $report->csi_compressed_air_monthly_report($date,$date2);
				$data['menutitle']='Сжатый воздух ЦСИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'csi_thermalclamping_water_monthly':
				$report = new Model_Csimonthlyreports();
				$result = $report->csi_thermalclamping_water_monthly_report($date,$date2);
				$data['menutitle']='Теплофикационная вода ЦСИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'electro_monthly':
				$report = new Model_Cshimonthlyreports();
				$result = $report->electro_monthly_report($date,$date2);
				$data['menutitle']='Электроэнергия.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'test_monthly':
				$report = new Model_Cshimonthlyreports();
				$result = $report->test_monthly_report($date,$date2);
				$data['menutitle']='Электроэнергия.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'cshi_rotating_oven_1_coke_gas_brigade':
				$report = new Model_Cshibrigadereports();
				$result = $report->cshi_rotating_oven_1_coke_gas_brigade_report($date,$date2,$brigade);
				$data['menutitle']='Коксовый газ ВП1 ЦШИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'cshi_rotating_oven_2_coke_gas_brigade':
				$report = new Model_Cshibrigadereports();
				$result = $report->cshi_rotating_oven_2_coke_gas_brigade_report($date,$date2,$brigade);
				$data['menutitle']='Коксовый газ ВП2 ЦШИ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'cshi_compressed_air_gas_cleaning_brigade':
				$report = new Model_Cshibrigadereports();
				$result = $report->cshi_compressed_air_gas_cleaning_brigade_report($date,$date2,$brigade);
				$data['menutitle']='Сжатый воздух ЦШИ - Газоочистка.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
				case 'drinking_water_1_monthly':
					$report = new Model_Cshimonthlyreports();
					$result = $report->drinking_water_1_monthly_report($date,$date2);
					$data['menutitle']='Пожарно-питьевая вода. Быт. ввод №2 (бойлер) ЦШИ. '.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
					break;
				case 'drinking_water_2_monthly':
					$report = new Model_Cshimonthlyreports();
					$result = $report->drinking_water_2_monthly_report($date,$date2);
					$data['menutitle']='Пожарно-питьевая вода. Быт. ввод №1 (зап. вых.) ЦШИ. '.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
					break;
				case 'drinking_water_3_monthly':
					$report = new Model_Cshimonthlyreports();
					$result = $report->drinking_water_3_monthly_report($date,$date2);
					$data['menutitle']='Пожарно-питьевая вода. Маст. энергослужбы ЦШИ. '.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
					break;
				case 'drinking_water_4_monthly':
					$report = new Model_Cshimonthlyreports();
					$result = $report->drinking_water_4_monthly_report($date,$date2);
					$data['menutitle']='Пожарно-питьевая вода. Пожарно-питьевая вода. ЦШИ АБК столовая. '.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
					break;
				case 'drinking_water_5_monthly':
					$report = new Model_Cshimonthlyreports();
					$result = $report->drinking_water_5_monthly_report($date,$date2);
					$data['menutitle']='Пожарно-питьевая вода. Пожарно-питьевая вода. Бытовые ЦСИ. '.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
					break;
				case 'drinking_water_6_monthly':
					$report = new Model_Cshimonthlyreports();
					$result = $report->drinking_water_6_monthly_report($date,$date2);
					$data['menutitle']='Пожарно-питьевая вода. Мех. мастерская. '.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
					break;
			// Общие данные
			case 'total_oxygen_daily':
				$report = new Model_askutotalreports();
				$result = $report->total_oxygen_daily_report($date);
				$data['menutitle']='Кислород.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'total_oxygen_monthly':
				$report = new Model_askutotalreports();
				$result = $report->total_oxygen_monthly_report($date,$date2);
				$data['menutitle']='Кислород.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'total_coke_gas_daily':
				$report = new Model_askutotalreports();
				$result = $report->total_coke_gas_daily_report($date);
				$data['menutitle']='Коксовый газ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'total_coke_gas_monthly':
				$report = new Model_askutotalreports();
				$result = $report->total_coke_gas_monthly_report($date,$date2);
				$data['menutitle']='Коксовый газ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'total_steam_daily':
				$report = new Model_askutotalreports();
				$result = $report->total_steam_daily_report($date);
				$data['menutitle']='Пар.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'total_steam_monthly':
				$report = new Model_askutotalreports();
				$result = $report->total_steam_monthly_report($date,$date2);
				$data['menutitle']='Пар.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'total_natural_gas_daily':
				$report = new Model_askutotalreports();
				$result = $report->total_natural_gas_daily_report($date);
				$data['menutitle']='Природный газ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'total_natural_gas_monthly':
				$report = new Model_askutotalreports();
				$result = $report->total_natural_gas_monthly_report($date,$date2);
				$data['menutitle']='Природный газ.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'total_compressed_air_daily':
				$report = new Model_askutotalreports();
				$result = $report->total_compressed_air_daily_report($date);
				$data['menutitle']='Сжатый воздух.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'total_compressed_air_monthly':
				$report = new Model_askutotalreports();
				$result = $report->total_compressed_air_monthly_report($date,$date2);
				$data['menutitle']='Сжатый воздух.'.$data['menutitle'];
				$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
			break;
			case 'cshi_analysis1_daily':
				$report = new Model_askutotalreports();
				$result = $report->cshi_analysis1_report($date);
				$data['menutitle']='Анализ энергоресурсов.'.$data['menutitle'].'. Коксовый газ.';
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			case 'cshi_analysis2_daily':
				$report = new Model_askutotalreports();
				$result = $report->cshi_analysis2_report($date);
				$data['menutitle']='Анализ энергоресурсов.'.$data['menutitle'].'. Сжатый воздух.';
				$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
			break;
			default:
				$data['menutitle']='Отчёты АСКУ ТЭР';
				$result ='';
			break;
			}
			$this->template->title = $data['menutitle'];
			if (count($result['data'])==0)
            {
				$error_data=array('error_topic'=>'Ошибка!','error_message'=>'Нет данных за указанный период времени!');
				$error_message_view = View::factory('/error_message',$error_data)->render();
				$content_view=$error_message_view;
            }
			else if ($media=="xls")
			{
				$ws = new Spreadsheet(array(
					'author' => 'Kohana-PHPExcel',
					'title' => 'Report',
					'subject' => 'Subject',
					'description' => 'Description',
				));
				$ws->set_response($this->response);
				$result['caption'] = $data['menutitle'];
				$result['reportdate'] = strip_tags($note);
				//print_r($result);exit;
				$ws->set_report_data($result,"Отчёт",array(0));
				$ws->send(array('name'=>'report', 'format'=>'Excel5'));
			}
			else if ($report_type=='cshi_analysis1_daily' || $report_type=='cshi_analysis2_daily')
			{
				$table1 = Table::factory($result['data'][0]);
				$table1->set_footer($result['footer']);
				$table1->set_attributes('id', 'report_table');
				$table1->set_column_titles($result['column_titles']);
				$table2 = Table::factory($result['data'][1]);
				$table2->set_footer($result['footer']);
				$table2->set_attributes('id', 'report_table');
				$table2->set_column_titles($result['column_titles']);
				$data['report_content']=$note.'<p style="font-size:12px;">8:00 - 20:00</p>'.$table1->render().'<p style="font-size:12px;">20:00 - 8:00</p>'.$table2->render();
				$content_view = View::factory('report_result_view',$data);
			}
			else if ($report_type=='test_daily')
			{
				$table = Table::factory($result['data']);
				$table->set_footer($result['footer']);
				$table->set_attributes('id', 'report_table');
				$table->set_column_titles($result['column_titles']);
				$data['report_content']=$note.$table->render();
				$content_view = View::factory('report_result_view',$data);
			}
			else
			{
				$table = Table::factory($result['data']);
				$table->set_footer($result['footer']);
				$table->set_attributes('id', 'report_table');
				$table->set_column_titles($result['column_titles']);
				$data['report_content']=$note.$table->render();
				$content_view = View::factory('report_result_view',$data);
			}
			$menu_view = View::factory('menu/main_menu',$data)->render();
			$this->template->menu = $menu_view;
			$local_menu_view = View::factory('menu/asku_reports_menu')->render();
			$this->template->local_menu = $local_menu_view;
			$this->template->content = $content_view;
		}
//------------------------------------------------------------------------------
		public function action_askureports_trend()
		{
			$styles = array('css/table.css'=>'screen',
                			'css/print.css'=>'print');
			$this->template->styles = $styles;
			$tmp_params = explode('&', $this->request->param('id','default'));
			foreach($tmp_params as $key => $value)
			{
				$tmp_param = explode('=', $value);
				$params[$tmp_param[0]]=$tmp_param[1];
			}
			$date = (isset($params['date1']))?$params['date1']:date('d-m-Y');
			$date2 = (isset($params['date2']))?$params['date2']:date('d-m-Y');
			$report_type = (isset($params['report_type']))?$params['report_type']:'cshi_oxygen_daily';
			$parameter = (isset($params['parameter']))?$params['parameter']:'1';
//			$report_type = $this->request->param('id','cshi_oxygen_daily');
//			$date = $this->request->param('id1',date('d-m-Y'));
//			$date2 = $this->request->param('id2',date('d-m-Y'));
//			$parameter = $this->request->param('id3','1');
			$date=str_replace('-','.',$date);
			$date2=str_replace('-','.',$date2);
			$tmp_date=new DateTime($date2);
			$tmp_date->add(new DateInterval('P1D'));
			$date3=$tmp_date->format('d.m.Y');
			switch (substr($report_type,strrpos($report_type,"_")+1)) {
				case 'monthly':
					$data['menutitle']=" Месячный отчёт. Отчёты АСКУ ТЭР";
				break;
				case 'daily':
					$data['menutitle']=" Суточный отчёт. Отчёты АСКУ ТЭР";
				break;
				case 'user':
					$data['menutitle']=" Пользовательский отчёт. Отчёты АСКУ ТЭР";
				break;
			}
			$report_type=str_replace("user","monthly",$report_type);
			switch ($report_type)
			{
				case 'cshi_oxygen_daily':
					$report = new Model_Cshidailyreports();
					$result = $report->cshi_oxygen_daily_report($date);
					$data['menutitle']='Кислород ЦШИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на '.$date.'</p>';
					$y_title="Объем, м³";
				  break;
				case 'cshi_rotating_oven_1_coke_gas_daily':
					$report = new Model_Cshidailyreports();
					$result = $report->cshi_rotating_oven_1_coke_gas_daily_report($date);
					$data['menutitle']='Коксовый газ вращ. печи №1 ЦШИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на '.$date.'</p>';
					$y_title="Объем, м³";
				  break;
				case 'cshi_rotating_oven_2_coke_gas_daily':
					$report = new Model_Cshidailyreports();
					$result = $report->cshi_rotating_oven_2_coke_gas_daily_report($date);
					$data['menutitle']='Коксовый газ вращ. печи №2 ЦШИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на '.$date.'</p>';
					$y_title="Объем, м³";
				  break;
				case 'cshi_rotary_drier1_coke_gas_daily':
					$report = new Model_Cshidailyreports();
					$result = $report->cshi_rotary_drier1_coke_gas_daily_report($date);
					$data['menutitle']='Коксовый газ суш. барабана №1 ЦШИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на '.$date.'</p>';
					$y_title="Объем, м³";
				  break;
				case 'cshi_rotary_drier2_coke_gas_daily':
					$report = new Model_Cshidailyreports();
					$result = $report->cshi_rotary_drier2_coke_gas_daily_report($date);
					$data['menutitle']='Коксовый газ суш. барабана №2 ЦШИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на '.$date.'</p>';
					$y_title="Объем, м³";
				  break;
				case 'cshi_rotary_drier3_coke_gas_daily':
					$report = new Model_Cshidailyreports();
					$result = $report->cshi_rotary_drier3_coke_gas_daily_report($date);
					$data['menutitle']='Коксовый газ суш. барабана №3 ЦШИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на '.$date.'</p>';
					$y_title="Объем, м³";
				  break;
				case 'csi_rotary_driers_coke_gas_DPU_daily':
					$report = new Model_Csidailyreports();
					$result = $report->csi_rotary_driers_coke_gas_DPU_daily_report($date);
					$data['menutitle']='Коксовый газ суш. барабаны ДПУ ЦСИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на '.$date.'</p>';
					$y_title="Объем, м³";
				  break;
				case 'csi_rotary_driers_coke_gas_DPandFU_daily':
					$report = new Model_Csidailyreports();
					$result = $report->csi_rotary_driers_coke_gas_DPandFU_daily_report($date);
					$data['menutitle']='Коксовый газ суш. барабаны ДП и ФУ ЦСИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на '.$date.'</p>';
					$y_title="Объем, м³";
				  break;
				case 'cshi_tunnel_furnaces_coke_gas_daily':
					$report = new Model_Cshidailyreports();
					$result = $report->cshi_tunnel_furnaces_coke_gas_daily_report($date);
					$data['menutitle']='Коксовый газ тун. печи ЦШИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на '.$date.'</p>';
					$y_title="Объем, м³";
				  break;
				case 'csi_forge_furnaces_coke_gas_daily':
					$report = new Model_Csidailyreports();
					$result = $report->csi_forge_furnaces_coke_gas_daily_report($date);
					$data['menutitle']='Коксовый газ кузнеч. печь ЦCИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на '.$date.'</p>';
					$y_title="Объем, м³";
				  break;
				case 'csi_coke_gas_heating_fu_1_2_daily':
					$report = new Model_Csidailyreports();
					$result = $report->csi_coke_gas_heating_fu_1_2_daily_report($date);
					$data['menutitle']='Коксовый газ цементационные печи №1,2 ЦСИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на '.$date.'</p>';
					$y_title="Объем, м³";
				  break;
				case 'cshi_natural_gas_daily':
					$report = new Model_Cshidailyreports();
					$result = $report->cshi_natural_gas_daily_report($date);
					$data['menutitle']='Природный газ ЦШИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на '.$date.'</p>';
					$y_title="Объем, м³";
				  break;
				case 'cshi_compressed_air_daily':
					$report = new Model_Cshidailyreports();
					$result = $report->cshi_compressed_air_daily_report($date);
					$data['menutitle']='Сжатый воздух ЦШИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на '.$date.'</p>';
					$y_title="Объем, м³";
				  break;
				case 'cshi_compressed_air_gas_cleaning_daily':
					$report = new Model_Cshidailyreports();
					$result = $report->cshi_compressed_air_gas_cleaning_daily_report($date);
					$data['menutitle']='Сжатый воздух ЦШИ (Газоочистка).'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на '.$date.'</p>';
					$y_title="Объем, м³";
				  break;
				case 'cshi_thermalclamping_water_daily':
					$report = new Model_Cshidailyreports();
					$result = $report->cshi_thermalclamping_water_daily_report($date);
					$data['menutitle']='Теплофикационная вода ЦШИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на '.$date.'</p>';
					$y_title="Объем, м³";
				  break;
				case 'electro_daily':
					$report = new Model_Cshidailyreports();
					$result = $report->electro_daily_report($date);
					$data['menutitle']='Электроэнергия.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на '.$date.'</p>';
					$y_title="Электроэнергия, кВт*ч";
				  break;
				case 'cshi_compressed_air_formovka_daily':
					$report = new Model_Cshidailyreports();
					$result = $report->cshi_compressed_air_formovka_daily_report($date);
					$data['menutitle']='Сжатый воздух формовка ЦШИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на '.$date.'</p>';
					$y_title="Объем, м³";
				  break;
				//Месячные отчёты;
				case 'cshi_oxygen_monthly':
					$report = new Model_Cshimonthlyreports();
					$result = $report->cshi_oxygen_monthly_report($date,$date2);
					$data['menutitle']='Кислород ЦШИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на период с '.$date.' по '.$date2.'</p>';
					$y_title="Объем, м³";
	  			break;
				case 'cshi_rotating_oven_1_coke_gas_monthly':
					$report = new Model_Cshimonthlyreports();
					$result = $report->cshi_rotating_oven_1_coke_gas_monthly_report($date,$date2);
					$data['menutitle']='Коксовый газ вращ. печи №1 ЦШИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на период с '.$date.' по '.$date2.'</p>';
					$y_title="Объем, м³";
	  			break;
				case 'cshi_rotating_oven_2_coke_gas_monthly':
					$report = new Model_Cshimonthlyreports();
					$result = $report->cshi_rotating_oven_2_coke_gas_monthly_report($date,$date2);
					$data['menutitle']='Коксовый газ вращ. печи №2 ЦШИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на период с '.$date.' по '.$date2.'</p>';
					$y_title="Объем, м³";
		  		break;
				case 'cshi_tunnel_furnaces_coke_gas_monthly':
					$report = new Model_Cshimonthlyreports();
					$result = $report->cshi_tunnel_furnaces_coke_gas_monthly_report($date,$date2);
					$data['menutitle']='Коксовый газ тун. печи ЦШИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на период с '.$date.' по '.$date2.'</p>';
					$y_title="Объем, м³";
			  	break;
				case 'cshi_rotary_drier1_coke_gas_monthly':
					$report = new Model_Cshimonthlyreports();
					$result = $report->cshi_rotary_drier1_coke_gas_monthly_report($date,$date2);
					$data['menutitle']='Коксовый газ суш. барабана №1 ЦШИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на период с '.$date.' по '.$date2.'</p>';
					$y_title="Объем, м³";
				  break;
				case 'cshi_rotary_drier2_coke_gas_monthly':
					$report = new Model_Cshimonthlyreports();
					$result = $report->cshi_rotary_drier2_coke_gas_monthly_report($date,$date2);
					$data['menutitle']='Коксовый газ суш. барабана №2 ЦШИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на период с '.$date.' по '.$date2.'</p>';
					$y_title="Объем, м³";
	  			break;
				case 'cshi_rotary_drier3_coke_gas_monthly':
					$report = new Model_Cshimonthlyreports();
					$result = $report->cshi_rotary_drier3_coke_gas_monthly_report($date,$date2);
					$data['menutitle']='Коксовый газ суш. барабана №3 ЦШИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на период с '.$date.' по '.$date2.'</p>';
					$y_title="Объем, м³";
	  			break;
				case 'csi_rotary_driers_coke_gas_DPU_monthly':
					$report = new Model_Csimonthlyreports();
					$result = $report->csi_rotary_driers_coke_gas_DPU_monthly_report($date,$date2);
					$data['menutitle']='Коксовый газ суш. барабаны ДПУ ЦСИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на период с '.$date.' по '.$date2.'</p>';
					$y_title="Объем, м³";
	  			break;
				case 'csi_rotary_driers_coke_gas_DPandFU_monthly':
					$report = new Model_Csimonthlyreports();
					$result = $report->csi_rotary_driers_coke_gas_DPandFU_monthly_report($date,$date2);
					$data['menutitle']='Коксовый газ суш. барабаны ДП и ФУ ЦСИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на период с '.$date.' по '.$date2.'</p>';
					$y_title="Объем, м³";
	  			break;
				case 'csi_forge_furnaces_coke_gas_monthly':
					$report = new Model_Csimonthlyreports();
					$result = $report->csi_forge_furnaces_coke_gas_monthly_report($date,$date2);
					$data['menutitle']='Коксовый газ кузнеч. печь.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на период с '.$date.' по '.$date2.'</p>';
					$y_title="Объем, м³";
	  			break;
				case 'csi_coke_gas_heating_fu_1_2_monthly':
					$report = new Model_Csimonthlyreports();
					$result = $report->csi_coke_gas_heating_fu_1_2_monthly_report($date,$date2);
					$data['menutitle']='Коксовый газ цементационные печи №1,2 ЦСИ'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на период с '.$date.' по '.$date2.'</p>';
					$y_title="Объем, м³";
	  			break;
				case 'cshi_natural_gas_monthly':
					$report = new Model_Cshimonthlyreports();
					$result = $report->cshi_natural_gas_monthly_report($date,$date2);
					$data['menutitle']='Природный газ ЦШИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на период с '.$date.' по '.$date2.'</p>';
					$y_title="Объем, м³";
	  			break;
				case 'cshi_compressed_air_gas_cleaning_monthly':
					$report = new Model_Cshimonthlyreports();
					$result = $report->cshi_compressed_air_gas_cleaning_monthly_report($date,$date2);
					$data['menutitle']='Сжатый воздух ЦШИ (Газоочистка).'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на период с '.$date.' по '.$date2.'</p>';
					$y_title="Объем, м³";
	  			break;
				case 'cshi_compressed_air_monthly':
					$report = new Model_Cshimonthlyreports();
					$result = $report->cshi_compressed_air_monthly_report($date,$date2);
					$data['menutitle']='Сжатый воздух ЦШИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на период с '.$date.' по '.$date2.'</p>';
					$y_title="Объем, м³";
	  			break;
				case 'cshi_thermalclamping_water_monthly':
					$report = new Model_Cshimonthlyreports();
					$result = $report->cshi_thermalclamping_water_monthly_report($date,$date2);
					$data['menutitle']='Теплофикационная вода ЦШИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на период с '.$date.' по '.$date2.'</p>';
					$y_title="Объем, м³";
	  			break;
				case 'csi_oxygen_daily':
					$report = new Model_Csidailyreports();
					$result = $report->csi_oxygen_daily_report($date);
					$data['menutitle']='Кислород ЦCИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на '.$date.'</p>';
					$y_title="Объем, м³";
	  			break;
				case 'csi_steam_daily':
					$report = new Model_Csidailyreports();
					$result = $report->csi_steam_daily_report($date);
					$data['menutitle']='Пар.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на '.$date.'</p>';
					$y_title="Тепловая энергия, ккал";
	  			break;
				case 'csi_steam_teh_daily':
					$report = new Model_Csidailyreports();
					$result = $report->csi_steam_teh_daily_report($date);
					$data['menutitle']='Пар ЦCИ - технология.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на '.$date.'</p>';
					$y_title="Тепловая энергия, ккал";
	  			break;
				case 'csi_compressed_air_daily':
					$report = new Model_Csidailyreports();
					$result = $report->csi_compressed_air_daily_report($date);
					$data['menutitle']='Сжатый воздух ЦСИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на '.$date.'</p>';
					$y_title="Объем, м³";
	  			break;
				case 'csi_thermalclamping_water_daily':
					$report = new Model_Csidailyreports();
					$result = $report->csi_thermalclamping_water_daily_report($date);
					$data['menutitle']='Теплофикационная вода ЦСИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на '.$date.'</p>';
					$y_title="Объем, м³";
	  			break;
				//
				case 'csi_oxygen_monthly':
					$report = new Model_Csimonthlyreports();
					$result = $report->csi_oxygen_monthly_report($date,$date2);
					$data['menutitle']='Кислород ЦСИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на период с '.$date.' по '.$date2.'</p>';
					$y_title="Объем, м³";
	  			break;
				case 'csi_steam_monthly':
					$report = new Model_Csimonthlyreports();
					$result = $report->csi_steam_monthly_report($date,$date2);
					$data['menutitle']='Пар.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на период с '.$date.' по '.$date2.'</p>';
					$y_title="Тепловая энергия, ккал";
	  			break;
				case 'csi_steam_teh_monthly':
					$report = new Model_Csimonthlyreports();
					$result = $report->csi_steam_teh_monthly_report($date,$date2);
					$data['menutitle']='Пар ЦСИ - технология.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на период с '.$date.' по '.$date2.'</p>';
					$y_title="Тепловая энергия, ккал";
	  			break;
				case 'csi_compressed_air_monthly':
					$report = new Model_Csimonthlyreports();
					$result = $report->csi_compressed_air_monthly_report($date,$date2);
					$data['menutitle']='Сжатый воздух ЦСИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на период с '.$date.' по '.$date2.'</p>';
					$y_title="Объем, м³";
	  			break;
				case 'csi_thermalclamping_water_monthly':
					$report = new Model_Csimonthlyreports();
					$result = $report->csi_thermalclamping_water_monthly_report($date,$date2);
					$data['menutitle']='Теплофикационная вода ЦСИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на период с '.$date.' по '.$date2.'</p>';
					$y_title="Объем, м³";
			  	break;
				case 'csi_natural_gas_daily':
					$report = new Model_Csidailyreports();
					$result = $report->csi_natural_gas_daily_report($date);
					$data['menutitle']='Природный газ ЦСИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на '.$date.'</p>';
					$y_title="Объем, м³";
			  	break;
				case 'cmdo_natural_gas_daily':
					$report = new Model_Cmdodailyreports();
					$result = $report->cmdo_natural_gas_daily_report($date);
					$data['menutitle']='Природный газ ЦМДО.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на '.$date.'</p>';
					$y_title="Объем, м³";
			  	break;
				case 'csi_natural_gas_monthly':
					$report = new Model_Csimonthlyreports();
					$result = $report->csi_natural_gas_monthly_report($date,$date2);
					$data['menutitle']='Природный газ ЦСИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на период с '.$date.' по '.$date2.'</p>';
					$y_title="Объем, м³";
			  	break;
				case 'cmdo_natural_gas_monthly':
					$report = new Model_Cmdomonthlyreports();
					$result = $report->cmdo_natural_gas_monthly_report($date,$date2);
					$data['menutitle']='Природный газ ЦМДО.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на период с '.$date.' по '.$date2.'</p>';
					$y_title="Объем, м³";
			  	break;
				case 'electro_monthly':
					$report = new Model_Cshimonthlyreports();
					$result = $report->electro_monthly_report($date,$date2);
					$data['menutitle']='Электроэнергия.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на период с '.$date.' по '.$date2.'</p>';
					$y_title="Электроэнергия, кВт*ч";
			  	break;
				case 'cshi_compressed_air_formovka_monthly':
					$report = new Model_Cshimonthlyreports();
					$result = $report->cshi_compressed_air_formovka_monthly_report($date,$date2);
					$data['menutitle']='Сжатый воздух формовка ЦШИ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">График составлен на период с '.$date.' по '.$date2.'</p>';
					$y_title="Объем, м³";
				  break;
				// Общие данные
				case 'total_oxygen_daily':
					$report = new Model_askutotalreports();
					$result = $report->total_oxygen_daily_report($date);
					$data['menutitle']='Кислород.'.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
				break;
				case 'total_oxygen_monthly':
					$report = new Model_askutotalreports();
					$result = $report->total_oxygen_monthly_report($date,$date2);
					$data['menutitle']='Кислород.'.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
				break;
				case 'total_coke_gas_daily':
					$report = new Model_askutotalreports();
					$result = $report->total_coke_gas_daily_report($date);
					$data['menutitle']='Коксовый газ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
				break;
				case 'total_coke_gas_monthly':
					$report = new Model_askutotalreports();
					$result = $report->total_coke_gas_monthly_report($date,$date2);
					$data['menutitle']='Коксовый газ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
				break;
				case 'total_steam_daily':
					$report = new Model_askutotalreports();
					$result = $report->total_steam_daily_report($date);
					$data['menutitle']='Пар.'.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
				break;
				case 'total_steam_monthly':
					$report = new Model_askutotalreports();
					$result = $report->total_steam_monthly_report($date,$date2);
					$data['menutitle']='Пар.'.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
				break;
				case 'total_natural_gas_daily':
					$report = new Model_askutotalreports();
					$result = $report->total_natural_gas_daily_report($date);
					$data['menutitle']='Природный газ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
				break;
				case 'total_natural_gas_monthly':
					$report = new Model_askutotalreports();
					$result = $report->total_natural_gas_monthly_report($date,$date2);
					$data['menutitle']='Природный газ.'.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
				break;
				case 'total_compressed_air_daily':
					$report = new Model_askutotalreports();
					$result = $report->total_compressed_air_daily_report($date);
					$data['menutitle']='Сжатый воздух.'.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
				break;
				case 'total_compressed_air_monthly':
					$report = new Model_askutotalreports();
					$result = $report->total_compressed_air_monthly_report($date,$date2);
					$data['menutitle']='Сжатый воздух.'.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
				break;
				case 'drinking_water_1_daily':
					$report = new Model_cshidailyreports();
					$result = $report->drinking_water_1_daily_report($date);
					$data['menutitle']='Пожарно-питьевая вода. Быт. ввод №2 (бойлер) ЦШИ. '.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
					break;
				case 'drinking_water_2_daily':
					$report = new Model_cshidailyreports();
					$result = $report->drinking_water_1_daily_report($date);
					$data['menutitle']='Пожарно-питьевая вода. Быт. ввод №1 (зап. вых.) ЦШИ '.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
					break;
				case 'drinking_water_3_daily':
					$report = new Model_cshidailyreports();
					$result = $report->drinking_water_1_daily_report($date);
					$data['menutitle']='Пожарно-питьевая вода. Маст. энергослужбы ЦШИ. '.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
					break;
				case 'drinking_water_4_daily':
					$report = new Model_cshidailyreports();
					$result = $report->drinking_water_1_daily_report($date);
					$data['menutitle']='Пожарно-питьевая вода. ЦШИ АБК столовая. '.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
					break;
				case 'drinking_water_5_daily':
					$report = new Model_cshidailyreports();
					$result = $report->drinking_water_1_daily_report($date);
					$data['menutitle']='Пожарно-питьевая вода. Бытовые ЦСИ. '.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
					break;
				case 'drinking_water_6_daily':
					$report = new Model_cshidailyreports();
					$result = $report->drinking_water_1_daily_report($date);
					$data['menutitle']='Пожарно-питьевая вода. Мех. мастерская. '.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на '.$date.'</p>';
					break;
				case 'drinking_water_1_monthly':
					$report = new Model_cshimonthlyreports();
					$result = $report->drinking_water_1_monthly_report($date);
					$data['menutitle']='Пожарно-питьевая вода. Быт. ввод №2 (бойлер) ЦШИ. '.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
					break;
				case 'drinking_water_2_monthly':
					$report = new Model_cshimonthlyreports();
					$result = $report->drinking_water_1_monthly_report($date);
					$data['menutitle']='Пожарно-питьевая вода. Быт. ввод №1 (зап. вых.) ЦШИ. '.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
					break;
				case 'drinking_water_3_monthly':
					$report = new Model_cshimonthlyreports();
					$result = $report->drinking_water_1_monthly_report($date);
					$data['menutitle']='Пожарно-питьевая вода. Маст. энергослужбы ЦШИ. '.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
					break;
				case 'drinking_water_4_monthly':
					$report = new Model_cshimonthlyreports();
					$result = $report->drinking_water_1_monthly_report($date);
					$data['menutitle']='Пожарно-питьевая вода. ЦШИ АБК столовая. '.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
					break;
				case 'drinking_water_5_monthly':
					$report = new Model_cshimonthlyreports();
					$result = $report->drinking_water_1_monthly_report($date);
					$data['menutitle']='Пожарно-питьевая вода. Бытовые ЦСИ. '.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
					break;
				case 'drinking_water_6_monthly':
					$report = new Model_cshimonthlyreports();
					$result = $report->drinking_water_1_monthly_report($date);
					$data['menutitle']='Пожарно-питьевая вода. Мех. мастерская. '.$data['menutitle'];
					$note='<p style="font-size:12px;">Отчёт составлен на период с '.$date.' по '.$date2.'</p>';
					break;

				default:
					$data['menutitle']='Отчёты АСКУ ТЭР';
					$result =array();
					$y_title="Объем, м³";
		  		break;
			}

			if (count($result["data"])!=0) {
				if ($report_type == 'cshi_compressed_air_gas_cleaning_monthly' or $report_type == 'cshi_compressed_air_gas_cleaning_daily'){
					$y_title=str_replace('Объем, м3 (V)','Расход, м3/ч (F)',str_replace(array('<sup>','</sup>'),'',$result["column_titles"][$parameter]));
					if ($parameter < 7)
					{
						$data['note']=$note;
						$data['img']="/ASUTP/php/trend_img_asku.php?a=".urlencode(join("&",array($report_type,$parameter,$date,$date3,$y_title)));
//						$data['parameters']=$result["column_titles"];
						$data['parameters'] = array_slice($result["column_titles"], 0, 6);
						$data['beg_time']=str_replace('.','-',$date);
						$data['end_time']=str_replace('.','-',$date3);
						$data['report_type']=$report_type;
						$content_view=View::factory('trend_asku',$data)->render();
					}
				}
				else
				{
					$y_title=str_replace('Объем, м3 (V)','Расход, м3/ч (F)',str_replace(array('<sup>','</sup>'),'',$result["column_titles"][$parameter]));
					$data['note']=$note;
					$data['img']="/ASUTP/php/trend_img_asku.php?a=".urlencode(join("&",array($report_type,$parameter,$date,$date3,$y_title)));
					//print_r($result["column_titles"]);
					$data['parameters']=$result["column_titles"];
					$data['beg_time']=str_replace('.','-',$date);
					$data['end_time']=str_replace('.','-',$date3);
					$data['report_type']=$report_type;
					$content_view=View::factory('trend_asku',$data)->render();
				}
			}
			else {
				$error_data=array('error_topic'=>'Ошибка!','error_message'=>'Нет данных в этом диапазоне!');
				$content_view = View::factory('/error_message',$error_data)->render();
			}

			$this->template->title = $data['menutitle'];
			$menu_view = View::factory('menu/main_menu',$data)->render();
			$this->template->menu = $menu_view;
			$local_menu_view = View::factory('menu/asku_reports_menu')->render();
			$this->template->local_menu = $local_menu_view;
			$this->template->content = $content_view;
		}
//------------------------------------------------------------------------------
		public function action_trend()
		{
			$styles = array('css/calendar/aqua/theme.css'=>'screen');
			$scripts = array('js/calendar/calendar-setup.js','js/calendar/lang/calendar-ru.js','js/calendar/calendar.js','js/calendar/checkDate.js');
			$this->template->styles = $styles;
			$this->template->scripts = $scripts;

//			$report_type = $this->request->param('id','press_current');
			$tmp_params = explode('&', $this->request->param('id','default'));
			foreach($tmp_params as $key => $value)
			{
				$tmp_param = explode('=', $value);
				$params[$tmp_param[0]]=$tmp_param[1];
			}
			$report_type = (isset($params['report_type']))?$params['report_type']:'press_current';

			$title = array("press_current"=>"График тока прессования. Отчёты ЦШИ",
							"press6_current"=>"График тока прессования пресса №6. Отчёты ЦШИ",
							"press7_current"=>"График тока прессования пресса №7. Отчёты ЦШИ",
							"press3_current"=>"График тока прессования пресса №3. Отчёты ЦШИ",
							"press9_current"=>"График тока прессования пресса №9. Отчёты ЦШИ",
							"press4_current"=>"График тока прессования пресса №4. Отчёты ЦШИ",
							"press8_current"=>"График тока прессования пресса №8. Отчёты ЦШИ",
							"press10_current"=>"График тока прессования пресса №10. Отчёты ЦШИ",
							"press11_current"=>"График тока прессования пресса №11. Отчёты ЦШИ",
							"mixer_stakan_current"=>"График тока стакана смесителя. Отчёты ЦШИ",
							"mixer_zavih_current"=>"График тока завихрителя смесителя. Отчёты ЦШИ",
							"tp1_teh"=>"Архив технологических параметров. Туннельная печь №1. Отчёты ЦШИ",
							"cshi_dry4"=>"Архив технологических параметров. Сушило №4. Отчёты ЦШИ",
							"drier_teh"=>"Архив технологических параметров. Камерные сушила. Отчёты ЦСИ",
							"csi_steamers"=>"Архив технологических параметров. Пропарочные камеры. Отчёты ЦСИ",
							"csi_tunnel"=>"Архив технологических параметров. Туннельные сушила. Отчёты ЦСИ",
							"cshi_rotary_furn"=>"Архив технологических параметров. Вращающиеся печи. Отчёты ЦШИ",
							"cshi_desiccators"=>"Архив технологических параметров. Сушильные барабаны. Отчёты ЦШИ",
							"csi_desiccators"=>"Архив технологических параметров. Сушильные барабаны. Отчёты ЦСИ",
							"csi_tunneldry"=>"Архив технологических параметров. Туннельные сушила. Отчёты ЦСИ",
							"cshi_carts"=>"Архив паспортов вагонов. Вагонные весы. Отчёты ЦШИ",
							);

			switch($report_type) {
				case 'tp1_teh':
					$form='cshi/reports/inquery_form_teh';
					$localmenu='menu/cshi_reports_menu';
				break;
				case 'cshi_dry4':
					$form='cshi/reports/inquery_form_teh';
					$localmenu='menu/cshi_reports_menu';
				break;
				case 'drier_teh':
					$form='csi/reports/inquery_form_teh';
					$localmenu='menu/csi_reports_menu';
				break;
				case 'cshi_rotary_furn':
					$form='cshi/reports/cshi_rotary_furn';
					$localmenu='menu/cshi_reports_menu';
				break;
				case 'cshi_desiccators':
					$form='cshi/reports/cshi_desiccators';
					$localmenu='menu/cshi_reports_menu';
				break;
				case 'csi_desiccators':
					$form='csi/reports/csi_desiccators';
					$localmenu='menu/csi_reports_menu';
				break;
				case 'csi_tunneldry':
					$form='csi/reports/csi_tunneldry';
					$localmenu='menu/csi_reports_menu';
				break;
				case 'csi_steamers':
					$form='csi/reports/steaming_chamber';
					$localmenu='menu/csi_reports_menu';
				break;
				case 'csi_tunnel':
					$form='csi/reports/tunnel_drier';
					$localmenu='menu/csi_reports_menu';
				break;
				default:
					$form='cshi/reports/inquery_form_graph';
					$localmenu='menu/cshi_reports_menu';
				break;
				case 'cshi_carts':
					$form='cshi/reports/cshi_carts';
					$localmenu='menu/cshi_reports_menu';
				break;
			}

			$data['menutitle']=$title[$report_type];
			$this->template->title =$data['menutitle'];
			$data['report_type']=$report_type;

			$this->template->menu = View::factory('menu/main_menu',$data)->render();
			$this->template->local_menu = View::factory($localmenu)->render();
			$this->template->content = View::factory($form,$data)->render();
		}
//------------------------------------------------------------------------------
		public function action_trend_result()
		{
			$styles = array('css/print.css'=>'print');
			$this->template->styles = $styles;
			$tmp_params = explode('&', $this->request->param('id','default'));
			foreach($tmp_params as $key => $value)
			{
				$tmp_param = explode('=', $value);
				$params[$tmp_param[0]]=$tmp_param[1];
			}
			$report_type = (isset($params['report_type']))?$params['report_type']:'press_current';
			$param_type = (isset($params['param_type']))?$params['param_type']:'T-T_SIGNAL_1';
			$date1 = (isset($params['date1']))?$params['date1']:date('Y-m-d');
			$date2 = (isset($params['date2']))?$params['date2']:date('Y-m-d');
			$sel_teh = (isset($params['sel_teh']))?$params['sel_teh']:'';
//			$report_type = $this->request->param('id','press_current');
//			$param_type = $this->request->param('id1','T-T_SIGNAL_1');
//			$date = $this->request->param('id2',date('Y-m-d'));
//			$date2 = $this->request->param('id3',date('Y-m-d'));
			$date_begin = new DateTime($date1);
			$date_end = new DateTime($date2);
			$param_type = str_replace('-','.',$param_type);
			$param_name = array(	"T.T_SIGNAL_01"=>"Температура сушила на позиции 3С(Л)",
									"T.T_SIGNAL_02"=>"Температура сушила на позиции 3С(П)",
									"T.T_SIGNAL_03"=>"Температура печи на позиции 5(Л)",
									"T.T_SIGNAL_04"=>"Температура печи на позиции 5(П)",
									"T.T_SIGNAL_05"=>"Температура печи на позиции 9(Л)",
									"T.T_SIGNAL_06"=>"Температура печи на позиции 13(Л)",
									"T.T_SIGNAL_07"=>"Температура печи на позиции 15(П)",
									"T.T_SIGNAL_08"=>"Температура печи на позиции 15(Л)",
									"T.T_SIGNAL_09"=>"Температура печи на позиции 18(П)",
									"T.T_SIGNAL_10"=>"Температура печи на позиции 19(Л)",
									"T.T_SIGNAL_11"=>"Температура печи на позиции 21(Л)",
									"T.T_SIGNAL_12"=>"Температура печи на позиции 22(Л)",
									"T.T_SIGNAL_13"=>"Температура печи на позиции 24(П)",
									"T.T_SIGNAL_14"=>"Температура печи на позиции 29(Л)",
									"T.T_SIGNAL_15"=>"Температура печи на позиции 35(П)",
									"T.T_SIGNAL_17"=>"Температура печи на позиции 40(Л)",
									"T.T_SIGNAL_18"=>"Температура отх. воз. поз 30-31",
									"T.T_SIGNAL_19"=>"Температура отх. воз. поз 34-35",
									"T.T_SIGNAL_24"=>"Температура рец. воздуха",
									"T.T_SIGNAL_25"=>"Температура отх. газов",
									"T.T_SIGNAL_26"=>"Температура сушила на позиции 10(Л)",
									"T.T_SIGNAL_27"=>"Температура сушила на позиции 10(П)",
									"T.T_SIGNAL_33"=>"Температура свода печи на позиции 15(СВ)",
									"T.T_SIGNAL_34"=>"Температура свода печи на позиции 20(СВ)",
									"T.T_SIGNAL_35"=>"Температура свода печи на позиции 23(СВ)",
									"T.T_SIGNAL_37"=>"Температура свода сушила №4",
									"T.T_SIGNAL_38"=>"Температура подаваемого воздуха сушила №4",
									"T.T_SIGNAL_39"=>"Температура сушила №4 бокавая (П)",
									"T.T_SIGNAL_40"=>"Температура сушила №4 бокавая (Л)",
									"T.T_SIGNAL_41"=>"Температура ТП1 23 левая",
									"T.T_SIGNAL_42"=>"Температура ТП1 22 правая",
									"T.T_SIGNAL_43"=>"Температура ТП3 23 левая",
									"T.T_SIGNAL_44"=>"Температура ТП3 22 правая",

									"F.F_SIGNAL_01"=>"Расход газа зона 1 Левая",
									"F.F_SIGNAL_02"=>"Расход газа зона 1 Правая",
									"F.F_SIGNAL_03"=>"Расход газа зона 2 Левая",
									"F.F_SIGNAL_04"=>"Расход газа зона 2 Правая",
									"F.F_SIGNAL_05"=>"Расход газа зона 3 Левая",
									"F.F_SIGNAL_06"=>"Расход газа зона 3 Правая",
									"F.F_SIGNAL_07"=>"Расход воздуха зона 1 Левая",
									"F.F_SIGNAL_08"=>"Расход воздуха зона 1 Правая",
									"F.F_SIGNAL_09"=>"Расход воздуха зона 2 Левая",
									"F.F_SIGNAL_10"=>"Расход воздуха зона 2 Правая",
									"F.F_SIGNAL_11"=>"Расход воздуха зона 3 Левая",
									"F.F_SIGNAL_12"=>"Расход воздуха зона 3 Правая",
									"F.F_SIGNAL_13"=>"Расход воздуха (Распределенная подача)",
									"F.F_SIGNAL_14"=>"Расход воздуха (Сосредоточенная подача)",
									"F.F_SIGNAL_15"=>"Расход воздуха (Рециркуляционный)",
									"F.F_SIGNAL_16"=>"Расход коксового газа",
									"F.F_SIGNAL_17"=>"Расход воздуха",
									"P.P_SIGNAL_01"=>"Давление в сушиле на позиции 3С",
									"P.P_SIGNAL_02"=>"Давление в сушиле на позиции 10С",
									"P.P_SIGNAL_04"=>"Давление в печи на позиции 5",
									"P.P_SIGNAL_05"=>"Давление в печи на позиции 15",
									"P.P_SIGNAL_08"=>"Давление в печи на позиции 33",
									"P.P_SIGNAL_09"=>"Давление в печи на позиции 38",
									"P.P_SIGNAL_10"=>"Давление газа в печь",
									"P.P_SIGNAL_11"=>"Давление отходящих газов",
									"P.P_SIGNAL_12"=>"Давление воздуха на горение",
									"P.P_SIGNAL_13"=>"Давление в зоне охлаждения",
									"P.P_SIGNAL_14"=>"Разряжение в зоне нагрева",
									"T.T_SIGNAL_36"=>"Показания пирометра",
									"PLC.IN_A.01"=>"Камерное сушило 1. Расход природ.газа",
									"PLC.IN_A.02"=>"Камерное сушило 1. Расход воздуха",
									"PLC.IN_A.03"=>"Камерное сушило 1. Давление природ.газа",
									"PLC.IN_A.04"=>"Камерное сушило 1. Давление воздуха",
									"PLC.IN_A.05"=>"Камерное сушило 1. Температура 1",
									"PLC.IN_A.06"=>"Камерное сушило 1. Температура 2",
									"PLC.IN_A.07"=>"Камерное сушило 1. Температура 3",
									"PLC.IN_A.14"=>"Камерное сушило 1. Положение ИМ расхода газа",
									"PLC.IN_A.15"=>"Камерное сушило 1. Положение ИМ расхода воздуха",
									"PLC.IN_A.08"=>"Камерное сушило 2. Расход природ.газа",
									"PLC.IN_A.09"=>"Камерное сушило 2. Расход воздуха",
									"PLC.IN_A.10"=>"Камерное сушило 2. Давление природ.газа",
									"PLC.IN_A.11"=>"Камерное сушило 2. Давление воздуха",
									"PLC.IN_A.12"=>"Камерное сушило 2. Температура 1",
									"PLC.IN_A.13"=>"Камерное сушило 2. Температура 2",
									"PLC.IN_A.17"=>"Камерное сушило 2. Положение ИМ расхода газа",
									"PLC.IN_A.18"=>"Камерное сушило 2. Положение ИМ расхода воздуха",
									"PLC.IN_A.19"=>"Камерное сушило 2. Температура 3",
									"PLC.IN_A.20"=>"Пропарочная камера 1. Температура",
									"PLC.IN_A.21"=>"Пропарочная камера 2. Температура",
									"PLC.IN_A.22"=>"Пропарочная камера 3. Температура",
									"PLC.IN_A.23"=>"Туннельное сушило 2. Температура 1",
									"PLC.IN_A.24"=>"Туннельное сушило 2. Температура 2",
									"PLC.IN_A.25"=>"Туннельное сушило 2. Температура 3",
									"PLC.IN_A.26"=>"Туннельное сушило 2. Температура 4",
									"PLC.IN_A.27"=>"Туннельное сушило 2. Температура 5",
									"PLC.IN_A.28"=>"Пропарочная камера 4. Температура",
									"PLC.IN_A.29"=>"Туннельное сушило 3. Температура 1",
									"PLC.IN_A.30"=>"Туннельное сушило 3. Температура 2",
									"PLC.IN_A.31"=>"Туннельное сушило 3. Температура 3",
									"PLC.IN_A.32"=>"Туннельное сушило 3. Температура 4",
									"PLC.IN_A.36"=>"Туннельное сушило 3. Температура 5",
									"PLC.IN_A.37"=>"Туннельное сушило 2. Расход горячего воздуха",
									"PLC.IN_A.38"=>"Туннельное сушило 3. Расход горячего воздуха",
									"PLC.IN_A.39"=>"Туннельное сушило 3. Температура перед дымососом",
									"PLC.IN_A.40"=>"Туннельное сушило 3. Разрежение перед дымососом",
									"PLC.IN_A.41"=>"Туннельное сушило 2. Положение ИМ",
									"PLC.IN_A.42"=>"Туннельное сушило 3. Положение ИМ",
									"PLC.IN_A.47"=>"Туннельное сушило 3. Температура подаваемого воздуха",
									"PLC.IN_A.48"=>"Туннельное сушило 2. Температура подаваемого воздуха",
									"PLC.IN_A.53"=>"Туннельное сушило 2. Температура перед дымососом",
									"PLC.IN_A.54"=>"Туннельное сушило 2. Разрежение перед дымососом",
									"PLC.IN_A.49"=>"Теплогенератор 1. Расход природного газа",
									"PLC.IN_A.50"=>"Теплогенератор 1. Давление природного газа",
									"PLC.IN_A.51"=>"Теплогенератор 1. Расход вент. воздуха",
									"PLC.IN_A.52"=>"Теплогенератор 1. Температура",
									"PLC.IN_A.55"=>"Теплогенератор 2. Расход природного газа",
									"PLC.IN_A.56"=>"Теплогенератор 2. Давление природного газа",
									"PLC.IN_A.57"=>"Теплогенератор 2. Расход вент. воздуха",
									"PLC.IN_A.58"=>"Теплогенератор 2. Температура",

									"3208"=>"Разряжение перед циклоном (лев.)",
									"3209"=>"Разряжение перед циклоном (прав.)",
									"3210"=>"Разряжение перед скруббером",
									"3211"=>"Разряжение перед дымососом",
									"3212"=>"Разряжение перед эл. фильтром (лев.)",
									"3213"=>"Разряжение перед эл. фильтром (прав.)",
									"3214"=>"Разряжение в пылевой камере",
									"3215"=>"Температура перед эл. фильтром (лев.)",
									"3216"=>"Температура перед эл. фильтром (прав.)",
									"3217"=>"Температура перед дымососом",
									"3218"=>"Температура в пылевой камере",
									"3219"=>"Анализ на СО",
									"3220"=>"Температура перед циклоном (лев.)",
									"3221"=>"Температура перед циклоном (прав.)",
									"3222"=>"Нагрузка питателя",
									"3224"=>"Q вентиляторного воздуха",
									"3174"=>"Сушильный барабан №1. Температура в топке",
									"3175"=>"Сушильный барабан №1. Температура на выходе",
									"3176"=>"Сушильный барабан №1. Разряжение",
									"3177"=>"Сушильный барабан №2. Температура в топке",
									"3178"=>"Сушильный барабан №2. Температура на выходе",
									"3179"=>"Сушильный барабан №2. Разряжение",
									"3180"=>"Сушильный барабан №3. Температура в топке",
									"3181"=>"Сушильный барабан №3. Температура на выходе",
									"3182"=>"Сушильный барабан №3. Разряжение",
									"1890"=>"Сушильный барабан №1. Температура в топке",
									"1891"=>"Сушильный барабан №1. Температура на выходе",
									"1889"=>"Сушильный барабан №1. Разряжение",
									"1888"=>"Сушильный барабан №1. Расход газа",
									"1899"=>"Сушильный барабан №2. Температура в топке",
									"1900"=>"Сушильный барабан №2. Температура на выходе",
									"1893"=>"Сушильный барабан №2. Разряжение",
									"1892"=>"Сушильный барабан №2. Расход газа",
									"3612"=>"Сжатый воздух ЦШИ (Газоочистка). Температура",
									"3613"=>"Сжатый воздух ЦШИ (Газоочистка). Давление",
									"3615"=>"Сжатый воздух ЦШИ (Газоочистка). Расход",
									"IN_A.IN_A_1"=>"Общая температура газа",
									"IN_A.IN_A_2"=>"Общее давление газа",
									"IN_A.IN_A_3"=>"Разряжение в пылевой камере",
									"IN_A.IN_A_4"=>"Разряжение перед скруббером",
									"IN_A.IN_A_5"=>"Разряжение перед циклоном левый канал",
									"IN_A.IN_A_6"=>"Разряжение перед циклоном правый канал",
									"IN_A.IN_A_7"=>"Разряжение перед электрофильтром левый канал",
									"IN_A.IN_A_8"=>"Разряжение перед электрофильтром правый канал",
									"IN_A.IN_A_9"=>"Разряжение перед дымососом",
									"IN_A.IN_A_10"=>"Температура в пылевой камере",
									"IN_A.IN_A_11"=>"Анализ на «СО»",
									"IN_A.IN_A_12"=>"Температура перед циклоном левый канал",
									"IN_A.IN_A_13"=>"Температура перед циклоном правый канал",
									"IN_A.IN_A_14"=>"Температура перед электрофильтром левый канал",
									"IN_A.IN_A_15"=>"Температура перед электрофильтром правый канал",
									"IN_A.IN_A_16"=>"Температура перед дымососом",
									"IN_A.IN_A_17"=>"Расход вентиляционного воздуха",
									"IN_A.IN_A_18"=>"Расход газа",
									"IN_A.IN_A_19"=>"Давление газа",
									"IN_A.IN_A_20"=>"Весы",
									"IN_A.IN_A_21"=>"Нагрузка питателя",
									"IN_A.IN_A_22"=>"Уровень в бункере выгрузки пыли",
									"IN_A.IN_A_23"=>"ИМ газ",
									"IN_A.IN_A_24"=>"ИМ воздух",
									"IN_A.IN_A_25"=>"Расход сжатого воздуха на газоочистку",
									"IN_A.IN_A_34"=>"Разрежение в пылевой камере",
									"IN_A.IN_A_35"=>"Разрежение перед скруббером",
									"IN_A.IN_A_36"=>"Разрежение перед циклоном левый канал",
									"IN_A.IN_A_37"=>"Разрежение перед циклоном правый канал",
									"IN_A.IN_A_38"=>"Разрежение перед электрофильтром левый канал",
									"IN_A.IN_A_39"=>"Разрежение перед электрофильтром правый канал",
									"IN_A.IN_A_40"=>"Разрежение перед дымососом",
									"IN_A.IN_A_41"=>"Температура в пылевой камере",
									"IN_A.IN_A_42"=>"Анализ на СО",
									"IN_A.IN_A_43"=>"Температура перед циклоном левый канал",
									"IN_A.IN_A_44"=>"Температура перед циклоном правый канал",
									"IN_A.IN_A_45"=>"Температура перед электрофильтром левый канал",
									"IN_A.IN_A_46"=>"Температура перед электрофильтром правый канал",
									"IN_A.IN_A_47"=>"Температура перед дымососом",
									"IN_A.IN_A_48"=>"Расход вентиляторного воздуха",
									"IN_A.IN_A_49"=>"Расход газа",
									"IN_A.IN_A_50"=>"Давление газа",
									"IN_A.IN_A_51"=>"Весы",
									"IN_A.IN_A_52"=>"Нагрузка питателя",
									"IN_A.IN_A_53"=>"Положение ИМ расхода газа",
									"IN_A.IN_A_54"=>"Положение ИМ расхода воздуха",
									"2091"=>"Перепад давления коксового газа нагревательной печи 1-2, Па",
									"2440"=>"Давление коксового газа нагревательной печи 1-2, кПа",
									"2441"=>"Температура коксового газа нагревательной печи 1-2, °С",
									"2445"=>"Расход коксового газа на нагревательную печь 1-2, м³/ч");

			$y_title_name = array (	"T.T_SIGNAL_01"=>"Температура, °С",			"T.T_SIGNAL_02"=>"Температура, °С",
									"T.T_SIGNAL_03"=>"Температура, °С",			"T.T_SIGNAL_04"=>"Температура, °С",
									"T.T_SIGNAL_05"=>"Температура, °С",			"T.T_SIGNAL_06"=>"Температура, °С",
									"T.T_SIGNAL_07"=>"Температура, °С",			"T.T_SIGNAL_08"=>"Температура, °С",
									"T.T_SIGNAL_09"=>"Температура, °С",			"T.T_SIGNAL_10"=>"Температура, °С",
									"T.T_SIGNAL_11"=>"Температура, °С",			"T.T_SIGNAL_12"=>"Температура, °С",
									"T.T_SIGNAL_13"=>"Температура, °С",			"T.T_SIGNAL_14"=>"Температура, °С",
									"T.T_SIGNAL_15"=>"Температура, °С",			"T.T_SIGNAL_17"=>"Температура, °С",
									"T.T_SIGNAL_18"=>"Температура, °С",			"T.T_SIGNAL_19"=>"Температура, °С",
									"T.T_SIGNAL_24"=>"Температура, °С",			"T.T_SIGNAL_25"=>"Температура, °С",
									"T.T_SIGNAL_26"=>"Температура, °С",			"T.T_SIGNAL_27"=>"Температура, °С",
									"T.T_SIGNAL_33"=>"Температура, °С",			"T.T_SIGNAL_34"=>"Температура, °С",
									"T.T_SIGNAL_35"=>"Температура, °С",
									"T.T_SIGNAL_41"=>"Температура, °С",
									"T.T_SIGNAL_42"=>"Температура, °С",
									"T.T_SIGNAL_43"=>"Температура, °С",
									"T.T_SIGNAL_44"=>"Температура, °С",
									"F.F_SIGNAL_01"=>"Pacxoд, м³/ч",
									"F.F_SIGNAL_02"=>"Pacxoд, м³/ч",			"F.F_SIGNAL_03"=>"Pacxoд, м³/ч",
									"F.F_SIGNAL_04"=>"Pacxoд, м³/ч",			"F.F_SIGNAL_05"=>"Pacxoд, м³/ч",
									"F.F_SIGNAL_06"=>"Pacxoд, м³/ч",			"F.F_SIGNAL_07"=>"Pacxoд, м³/ч",
									"F.F_SIGNAL_08"=>"Pacxoд, м³/ч",			"F.F_SIGNAL_09"=>"Pacxoд, м³/ч",
									"F.F_SIGNAL_10"=>"Pacxoд, м³/ч",			"F.F_SIGNAL_11"=>"Pacxoд, м³/ч",
									"F.F_SIGNAL_12"=>"Pacxoд, м³/ч",			"F.F_SIGNAL_13"=>"Pacxoд, м³/ч",
									"F.F_SIGNAL_14"=>"Pacxoд, м³/ч",			"F.F_SIGNAL_15"=>"Pacxoд, м³/ч",
									"F.F_SIGNAL_16"=>"Pacxoд, м³/ч",			"F.F_SIGNAL_17"=>"Pacxoд, м³/ч",
									"P.P_SIGNAL_01"=>"Давление, кгс/м²",		"P.P_SIGNAL_02"=>"Давление, кгс/м²",
									"P.P_SIGNAL_04"=>"Давление, кгс/м²",		"P.P_SIGNAL_05"=>"Давление, кгс/м²",
									"P.P_SIGNAL_08"=>"Давление, кгс/м²",		"P.P_SIGNAL_09"=>"Давление, кгс/м²",
									"P.P_SIGNAL_10"=>"Давление, кгс/м²",		"P.P_SIGNAL_11"=>"Давление, кгс/м²",
									"P.P_SIGNAL_12"=>"Давление, кгс/м²",		"P.P_SIGNAL_13"=>"Давление, кгс/м²",
									"P.P_SIGNAL_14"=>"Давление, кгс/м²",		"T.T_SIGNAL_34"=>"Температура, °С",
																				"T.T_SIGNAL_37"=>"Температура, °С",
																				"T.T_SIGNAL_38"=>"Температура, °С",
																				"T.T_SIGNAL_39"=>"Температура, °С",
																				"T.T_SIGNAL_40"=>"Температура, °С",
									"PLC.IN_A.01"=>"Pacxoд, м³/ч",				"PLC.IN_A.02"=>"Pacxoд, 10³ м³/ч",
									"PLC.IN_A.03"=>"Давление, кПа",				"PLC.IN_A.04"=>"Давление, кПа",
									"PLC.IN_A.05"=>"Температура, °С",			"PLC.IN_A.06"=>"Температура, °С",
									"PLC.IN_A.07"=>"Температура, °С",			"PLC.IN_A.14"=>"Положение, %",
									"PLC.IN_A.15"=>"Положение, %",				"PLC.IN_A.08"=>"Pacxoд, м³/ч",
									"PLC.IN_A.09"=>"Pacxoд, 10³ м³/ч",			"PLC.IN_A.10"=>"Давление, кПа",
									"PLC.IN_A.11"=>"Давление, кПа",				"PLC.IN_A.12"=>"Температура, °С",
									"PLC.IN_A.13"=>"Температура, °С",			"PLC.IN_A.17"=>"Положение, %",
									"PLC.IN_A.18"=>"Положение, %",				"PLC.IN_A.19"=>"Температура, °С",
									"PLC.IN_A.20"=>"Температура, °С",			"PLC.IN_A.21"=>"Температура, °С",
									"PLC.IN_A.22"=>"Температура, °С",			"PLC.IN_A.23"=>"Температура, °С",
									"PLC.IN_A.24"=>"Температура, °С",			"PLC.IN_A.25"=>"Температура, °С",
									"PLC.IN_A.26"=>"Температура, °С",			"PLC.IN_A.27"=>"Температура, °С",
									"PLC.IN_A.28"=>"Температура, °С",			"3208"=>"Разряжение, Па",
									"3209"=>"Разряжение, Па",					"3210"=>"Разряжение, Па",
									"3211"=>"Разряжение, Па",					"3212"=>"Разряжение, Па",
									"3213"=>"Разряжение, Па",					"3214"=>"Разряжение, Па",
									"3215"=>"Температура, °С",					"3216"=>"Температура, °С",
									"3217"=>"Температура, °С",					"3218"=>"Температура, °С",
									"3219"=>"",									"3220"=>"Температура, °С",
									"3221"=>"Температура, °С",					"3222"=>"Нагрузка, В",
									"3223"=>"Q, м3/ч",							"3174"=>"Температура, °С",
									"3175"=>"Температура, °С",					"3176"=>"Разряжение, Па",
									"3177"=>"Температура, °С",					"3178"=>"Температура, °С",
									"3179"=>"Разряжение, Па",					"3180"=>"Температура, °С",
									"3181"=>"Температура, °С",					"3182"=>"Разряжение, Па",
									"1890"=>"Температура, °С",					"1891"=>"Температура, °С",
									"1889"=>"Разряжение, Па",					"1888"=>"Pacxoд, м³/ч",
									"1899"=>"Температура, °С",					"1900"=>"Температура, °С",
									"1893"=>"Разряжение, Па",					"1892"=>"Pacxoд, м³/ч",
									"IN_A.IN_A_1"=>"Температура, °С",			"3612"=>"Температура, °С",
									"IN_A.IN_A_2"=>"Давление, кПа",				"3613"=>"Давление, кПа",
									"IN_A.IN_A_3"=>"Разряжение, Па",			"3615"=>"Расход, м³/ч",
									"IN_A.IN_A_4"=>"Разряжение, Па",
									"IN_A.IN_A_5"=>"Разряжение, Па",
									"IN_A.IN_A_6"=>"Разряжение, Па",
									"IN_A.IN_A_7"=>"Разряжение, Па",
									"IN_A.IN_A_8"=>"Разряжение, Па",
									"IN_A.IN_A_9"=>"Разряжение, Па",
									"IN_A.IN_A_10"=>"Температура, °С",
									"IN_A.IN_A_11"=>"%",
									"IN_A.IN_A_12"=>"Температура, °С",
									"IN_A.IN_A_13"=>"Температура, °С",
									"IN_A.IN_A_14"=>"Температура, °С",
									"IN_A.IN_A_15"=>"Температура, °С",
									"IN_A.IN_A_16"=>"Температура, °С",
									"IN_A.IN_A_17"=>"Pacxoд, м³/ч",
									"IN_A.IN_A_18"=>"Pacxoд, м³/ч",
									"IN_A.IN_A_19"=>"Давление, кПа",
									"IN_A.IN_A_20"=>"%",
									"IN_A.IN_A_21"=>"Нагрузка, В",
									"IN_A.IN_A_22"=>"Уровень, %",
									"IN_A.IN_A_23"=>"%",
									"IN_A.IN_A_24"=>"%",
									"IN_A.IN_A_25"=>"Pacxoд, м³/ч",
									"IN_A.IN_A_34"=>"Разряжение, Па",
									"IN_A.IN_A_35"=>"Разряжение, Па",
									"IN_A.IN_A_36"=>"Разряжение, Па",
									"IN_A.IN_A_37"=>"Разряжение, Па",
									"IN_A.IN_A_38"=>"Разряжение, Па",
									"IN_A.IN_A_39"=>"Разряжение, Па",
									"IN_A.IN_A_40"=>"Разряжение, Па",
									"IN_A.IN_A_41"=>"Температура, °С",
									"IN_A.IN_A_42"=>"%",
									"IN_A.IN_A_43"=>"Температура, °С",
									"IN_A.IN_A_44"=>"Температура, °С",
									"IN_A.IN_A_45"=>"Температура, °С",
									"IN_A.IN_A_46"=>"Температура, °С",
									"IN_A.IN_A_47"=>"Температура, °С",
									"IN_A.IN_A_48"=>"Pacxoд, м³/ч",
									"IN_A.IN_A_49"=>"Pacxoд, м³/ч",
									"IN_A.IN_A_50"=>"Давление, кПа",
									"IN_A.IN_A_51"=>"%",
									"IN_A.IN_A_52"=>"Нагрузка, В",
									"IN_A.IN_A_53"=>"Положение, %",
									"IN_A.IN_A_54"=>"Положение, %",
									"PLC.IN_A.29"=>"Температура, °С",
									"PLC.IN_A.30"=>"Температура, °С",
									"PLC.IN_A.31"=>"Температура, °С",
									"PLC.IN_A.32"=>"Температура, °С",
									"PLC.IN_A.36"=>"Температура, °С",
									"PLC.IN_A.37"=>"Расход, м³/ч",
									"PLC.IN_A.38"=>"Расход, м³/ч",
									"PLC.IN_A.39"=>"Температура, °С",
									"PLC.IN_A.40"=>"Разрежение, Па",
									"PLC.IN_A.41"=>"Положение, %",
									"PLC.IN_A.42"=>"Положение, %",
									"PLC.IN_A.47"=>"Температура, °С",
									"PLC.IN_A.48"=>"Температура, °С",
									"PLC.IN_A.53"=>"Температура, °С",
									"PLC.IN_A.54"=>"Разрежение, Па",
									"PLC.IN_A.49"=>"Расход, м³/ч",
									"PLC.IN_A.50"=>"Давление, кПа",
									"PLC.IN_A.51"=>"Расход, м³/ч",
									"PLC.IN_A.52"=>"Температура, °С",
									"PLC.IN_A.55"=>"Расход, м³/ч",
									"PLC.IN_A.56"=>"Давление, кПа",
									"PLC.IN_A.57"=>"Расход, м³/ч",
									"PLC.IN_A.58"=>"Температура, °С",
									"2091"=>"Перепад, кПа",
									"2440"=>"Давление, кПа",
									"2441"=>"Температура, °С",
									"2445"=>"Расход, м³/ч");

			switch ($report_type) {
			case 'press_current':
				$data['menutitle']='График тока прессования. Отчёты ЦШИ';
				$data['Database']='PFU';
				$data['Tag']='227';
				$data['Ratio']=150/4096;
				$localmenu='menu/cshi_reports_menu';
				$y_title="Ток, А";
			break;
			case 'press6_current':
				$data['menutitle']='График тока прессования пресса №6. Отчёты ЦШИ';
				$data['Database']='PFU5';
				$data['Tag']='6';
				$data['Ratio']=1;
				$localmenu='menu/cshi_reports_menu';
				$y_title="Ток, А";
			break;
			case 'press7_current':
				$data['menutitle']='График тока прессования пресса №7. Отчёты ЦШИ';
				$data['Database']='PFU5';
				$data['Tag']='7';
				$data['Ratio']=1;
				$localmenu='menu/cshi_reports_menu';
				$y_title="Ток, А";
			break;
			case 'press3_current':
				$data['menutitle']='График тока прессования пресса №3. Отчёты ЦШИ';
				$data['Database']='PFU5';
				$data['Tag']='8';
				$data['Ratio']=1;
				$localmenu='menu/cshi_reports_menu';
				$y_title="Ток, А";
			break;
			case 'press9_current':
				$data['menutitle']='График тока прессования пресса №9. Отчёты ЦШИ';
				$data['Database']='PFU5';
				$data['Tag']='9';
				$data['Ratio']=1;
				$localmenu='menu/cshi_reports_menu';
				$y_title="Ток, А";
			break;
			case 'press4_current':
				$data['menutitle']='График тока прессования пресса №4. Отчёты ЦШИ';
				$data['Database']='PFU5';
				$data['Tag']='10';
				$data['Ratio']=1;
				$localmenu='menu/cshi_reports_menu';
				$y_title="Ток, А";
			break;
			case 'press8_current':
				$data['menutitle']='График тока прессования пресса №8. Отчёты ЦШИ';
				$data['Database']='PFU5';
				$data['Tag']='11';
				$data['Ratio']=1;
				$localmenu='menu/cshi_reports_menu';
				$y_title="Ток, А";
			break;
			case 'press10_current':
				$data['menutitle']='График тока прессования пресса №10. Отчёты ЦШИ';
				$data['Database']='PFU5';
				$data['Tag']='12';
				$data['Ratio']=1;
				$localmenu='menu/cshi_reports_menu';
				$y_title="Ток, А";
			break;
			case 'press11_current':
				$data['menutitle']='График тока прессования пресса №11. Отчёты ЦШИ';
				$data['Database']='PFU5';
				$data['Tag']='13';
				$data['Ratio']=1;
				$localmenu='menu/cshi_reports_menu';
				$y_title="Ток, А";
			break;
			case 'mixer_stakan_current':
				$data['menutitle']='График тока стакана смесителя. Отчёты ЦШИ';
				$data['Database']='PFU';
				$data['Tag']='185';
				$data['Ratio']=30/4096;
				$localmenu='menu/cshi_reports_menu';
				$y_title="Ток, А";
			break;
			case 'mixer_zavih_current':
				$data['menutitle']='График тока завихрителя смесителя. Отчёты ЦШИ';
				$data['Database']='PFU';
				$data['Tag']='186';
				$data['Ratio']=150/4096;
				$localmenu='menu/cshi_reports_menu';
				$y_title="Ток, А";
			break;
			case 'tp1_teh':
				$data['menutitle']=$param_name[$param_type].'. Туннельная печь №1. Отчёты ЦШИ';
				$data['Database']='Tun_furn';
				$data['Tag']=$param_type;
				$data['Ratio']=1;
				$localmenu='menu/cshi_reports_menu';
				$y_title=$y_title_name[$param_type];
			break;
			case 'cshi_dry4':
				$data['menutitle']=$param_name[$param_type].'. Сушило №4. Отчёты ЦШИ';
				$data['Database']='Tun_furn';
				$data['Tag']=$param_type;
				$data['Ratio']=1;
				$localmenu='menu/cshi_reports_menu';
				$y_title=$y_title_name[$param_type];
			break;
			case 'drier_teh':
				$data['menutitle']=$param_name[$param_type].'. Камерные сушила. Отчёты ЦШИ';
				$data['Database']='CSI';
				$data['Tag']=$param_type;
				$data['Ratio']=1;
				$localmenu='menu/csi_reports_menu';
				$y_title=$y_title_name[$param_type];
			break;
			case 'cshi_rotary_furn':
				$data['menutitle']=$param_name[$param_type].'. Вращающаяся печь №1. Отчёты ЦШИ';
				$data['Database']='RF';
				$data['Tag']=$param_type;
				$data['Ratio']=1;
				$localmenu='menu/cshi_reports_menu';
				$y_title=$y_title_name[$param_type];
			break;
			case 'cshi_rotary_furn2':
				$data['menutitle']=$param_name[$param_type].'. Вращающаяся печь №2. Отчёты ЦШИ';
				$data['Database']='RF';
				$data['Tag']=$param_type;
				$data['Ratio']=1;
				$localmenu='menu/cshi_reports_menu';
				$y_title=$y_title_name[$param_type];
			break;
			case 'cshi_desiccators':
				$data['menutitle']=$param_name[$param_type].'. Сушильные барабаны. Отчёты ЦШИ';
				$data['Database']='ASKU3';
				$data['Tag']=$param_type;
				$data['Ratio']=1;
				$localmenu='menu/cshi_reports_menu';
				$y_title=$y_title_name[$param_type];
			break;
/*			case 'cshi_carts':
				$data['menutitle']=$param_name[$param_type].'. Вагонные весы. Отчёты ЦШИ';
				$data['Database']='Vesiterm';
				$data['Tag']=$param_type;
				$data['Ratio']=1;
				$localmenu='menu/cshi_reports_menu';
				$y_title=$y_title_name[$param_type];
			break;*/
			case 'csi_desiccators':
				$data['menutitle']=$param_name[$param_type].'. Сушильные барабаны. Отчёты ЦСИ';
				$data['Database']='ASKU3';
				$data['Tag']=$param_type;
				$data['Ratio']=1;
				$localmenu='menu/csi_reports_menu';
				$y_title=$y_title_name[$param_type];
			break;
			case 'csi_tunneldry':
				$data['menutitle']=$param_name[$param_type].'. Туннельные сушила. Отчёты ЦСИ';
				$data['Database']='ASKU3';
				$data['Tag']=$param_type;
				$data['Ratio']=1;
				$localmenu='menu/csi_reports_menu';
				$y_title=$y_title_name[$param_type];
			break;
			case 'csi_steamers':
				$data['menutitle']=$param_name[$param_type].'. Пропарочные камеры. Отчёты ЦШИ';
				$data['Database']='CSI';
				$data['Tag']=$param_type;
				$data['Ratio']=1;
				$localmenu='menu/csi_reports_menu';
				$y_title=$y_title_name[$param_type];
			break;
			case 'csi_tunnel':
				$data['menutitle']=$param_name[$param_type].'. Туннельные сушила. Отчёты ЦШИ';
				$data['Database']='CSI';
				$data['Tag']=$param_type;
				$data['Ratio']=1;
				$localmenu='menu/csi_reports_menu';
				$y_title=$y_title_name[$param_type];
			break;
			}
			$this->template->title =$data['menutitle'];
			//$date = $this->request->param('id2',date('Y-m-d'));
			//$date2 = $this->request->param('id3',date('Y-m-d'));
			//$date_begin = new DateTime($date);
			//$date_end = new DateTime($date2);
			$data['Begintime']=$date_begin->format('Y-m-d%20H:i:s');
			$data['Endtime']=$date_end->format('Y-m-d%20H:i:s');
			$data['Width']=955;
			$data['Height']=800;

			$trend=new Model_Trend;

			switch ($trend->Check($data)) {
			case 1:
				$data['note']="График составлен на период с ".$date_begin->format('d.m.Y H:i:s')." по ".$date_end->format('d.m.Y H:i:s');
				$data['img']="/ASUTP/php/trend_img.php?a=".urlencode(join("&",array($data['Database'],$data['Tag'],$data['Begintime'],$data['Endtime'],$data['Width'],$data['Height'],$data['Ratio'],$y_title)));
				$content_view=View::factory('trend',$data)->render();
			break;
			case -1:
				$error_data=array('error_topic'=>'Ошибка!','error_message'=>'Неверно указан источник данных!');
				$error_message_view = View::factory('/error_message',$error_data)->render();
				$content_view=$error_message_view;
			break;
			case -2:
				$error_data=array('error_topic'=>'Ошибка!','error_message'=>'В этом диапазоне нет данных!');
				$error_message_view = View::factory('/error_message',$error_data)->render();
				$content_view=$error_message_view;
			break;
			case -3:
				$error_data=array('error_topic'=>'Ошибка!','error_message'=>'Ошибка связи с базой данных!');
				$error_message_view = View::factory('/error_message',$error_data)->render();
				$content_view=$error_message_view;
			break;
			}
			$this->template->menu = View::factory('menu/main_menu',$data)->render();
			$this->template->local_menu = View::factory($localmenu)->render();
			$this->template->content = $content_view;

		}
//------------------------------------------------------------------------------
		public function action_cshireports_tp1_passport()
		{
			$styles = array('css/calendar/aqua/theme.css'=>'screen',
			                'css/table.css'=>'screen',
			                'css/print.css'=>'print');
			$scripts = array('js/calendar/calendar-setup.js','js/calendar/lang/calendar-ru.js','js/calendar/calendar.js','js/calendar/checkDate.js');
			$this->template->styles = $styles;
			$this->template->scripts = $scripts;

			$data['menutitle']="Паспорта вагонов. Отчёты ЦШИ";

			$dt=new DateTime(date(''));
			$tmp_params = explode('&', $this->request->param('id','default'));
			foreach($tmp_params as $key => $value)
			{
				$tmp_param = explode('=', $value);
				$params[$tmp_param[0]]=$tmp_param[1];
			}
			$dt_end = (isset($params['dt_end']))?$params['dt_end']:$dt->format('d.m.Y H:i');
			$dt->sub(new DateInterval('PT40H'));
			$dt_beg = (isset($params['dt_beg']))?$params['dt_beg']:$dt->format('d.m.Y H:i');
			$dt_beg=str_replace('-','.',$dt_beg);
			$dt_end=str_replace('-','.',$dt_end);

			$report = new Model_Cshitp1reports();
			$result = $report->GetTruckList($dt_beg,$dt_end);

			foreach ($result['data'] as &$value) {
				//$value['button']='<input type = "submit" value = "Паспорт" onclick = "javascript:location.href = /ASUTP/localmenu/cshireports_tp1_passport_result/'.str_replace(".","-",$value['DT_dry_beg']).'">';
				$value['CartNum']='<a href=/ASUTP/localmenu/cshireports_tp1_passport_result/dt='.str_replace(" ","%20",str_replace(".","-",$value['DT_dry_beg'])).'>'.$value['CartNum'].'</a>';
			}
			if (strtotime($dt_beg)>strtotime($dt_end)) {
				$error_data=array('error_topic'=>'Ошибка!','error_message'=>'Начальная дата больше конечной!');
				$data['table'] = View::factory('/error_message',$error_data)->render();
			}
			else if (count($result['data'])!=0) {
				$table = Table::factory($result['data']);
				$table->set_attributes('id', 'report_table');
				$table->set_column_titles($result['header']);
				$data['table'] = $table->render();
			}
			else {
				$error_data=array('error_topic'=>'Ошибка!','error_message'=>'Нет данных за указанный период времени!');
				$data['table'] = View::factory('/error_message',$error_data)->render();
			}
			$data['dt_beg'] = $dt_beg;
			$data['dt_end'] = $dt_end;

			$this->template->title = $data['menutitle'];
			$this->template->menu = View::factory('menu/main_menu',$data)->render();
			$this->template->local_menu = View::factory('menu/cshi_reports_menu')->render();
			$this->template->content = View::factory('cshi/reports/cshi_tp1_inquery',$data)->render();
		}
//------------------------------------------------------------------------------
		public function action_cshireports_tp1_passport_result()
		{
			$styles = array('css/passport_table.css'=>'screen',
							'css/table.css'=>'screen',
			                'css/print.css'=>'print');
			$this->template->styles = $styles;

			$data['menutitle']="Паспорта вагонов. Отчёты ЦШИ";
			$tmp_params = explode('&', $this->request->param('id','default'));
			foreach($tmp_params as $key => $value)
			{
				$tmp_param = explode('=', $value);
				$params[$tmp_param[0]]=$tmp_param[1];
			}
			$dt = (isset($params['dt']))?$params['dt']:'';
			$report = new Model_Cshitp1reports();
			$result = $report->GetPassport($dt);

			$this->template->title = $data['menutitle'];
			$this->template->menu = View::factory('menu/main_menu',$data)->render();
			$this->template->local_menu = View::factory('menu/cshi_reports_menu')->render();
			$this->template->content = View::factory('cshi/reports/cshi_tp1_passport',$result)->render();
		}
/* ------------------------------------- Код для отчетов по тележкам туннельной печи для АРМа технолога ---------------------------------- */
		public function action_cshireports_tp1_passport2()
		{
			$styles = array('css/calendar/aqua/theme.css'=>'screen',
			                'css/table.css'=>'screen',
			                'css/print.css'=>'print');
			$scripts = array('js/calendar/calendar-setup.js','js/calendar/lang/calendar-ru.js','js/calendar/calendar.js','js/calendar/checkDate.js');
			$this->template->styles = $styles;
			$this->template->scripts = $scripts;

			$data['menutitle']="Паспорта вагонов. Отчёты ЦШИ";

			$dt=new DateTime(date(''));
			$tmp_params = explode('&', $this->request->param('id','default'));
			foreach($tmp_params as $key => $value)
			{
				$tmp_param = explode('=', $value);
				$params[$tmp_param[0]]=$tmp_param[1];
			}
			$dt_end = (isset($params['dt_end']))?$params['dt_end']:$dt->format('d.m.Y H:i');
			$dt->sub(new DateInterval('PT40H'));
			$dt_beg = (isset($params['dt_beg']))?$params['dt_beg']:$dt->format('d.m.Y H:i');
			$dt_beg=str_replace('-','.',$dt_beg);
			$dt_end=str_replace('-','.',$dt_end);

			$report = new Model_Cshitp1reports();
			$result = $report->GetTruckList($dt_beg,$dt_end);

			foreach ($result['data'] as &$value) {
				$value['CartNum']='<a href=/ASUTP/localmenu/cshireports_tp1_passport_result2/dt='.str_replace(" ","%20",str_replace(".","-",$value['DT_dry_beg'])).'>'.$value['CartNum'].'</a>';
			}
			if (strtotime($dt_beg)>strtotime($dt_end)) {
				$error_data=array('error_topic'=>'Ошибка!','error_message'=>'Начальная дата больше конечной!');
				$data['table'] = View::factory('/error_message',$error_data)->render();
			}
			else if (count($result['data'])!=0) {
				$table = Table::factory($result['data']);
				$table->set_attributes('id', 'report_table');
				$table->set_column_titles($result['header']);
				$data['table'] = $table->render();
			}
			else {
				$error_data=array('error_topic'=>'Ошибка!','error_message'=>'Нет данных за указанный период времени!');
				$data['table'] = View::factory('/error_message',$error_data)->render();
			}
			$data['dt_beg'] = $dt_beg;
			$data['dt_end'] = $dt_end;

			$this->template->title = $data['menutitle'];
			$this->template->content = View::factory('cshi/reports/cshi_tp1_inquery2',$data)->render();
		}
		public function action_cshireports_tp1_passport_result2()
		{
			$styles = array('css/passport_table2.css'=>'screen',
			                'css/print.css'=>'print');
			$this->template->styles = $styles;

			$data['menutitle']="Паспорта вагонов. Отчёты ЦШИ";
			$tmp_params = explode('&', $this->request->param('id','default'));
			foreach($tmp_params as $key => $value)
			{
				$tmp_param = explode('=', $value);
				$params[$tmp_param[0]]=$tmp_param[1];
			}
			$dt = (isset($params['dt']))?$params['dt']:'';
			$report = new Model_Cshitp1reports();
			$result = $report->GetPassport($dt);

			$this->template->title = $data['menutitle'];
			$this->template->content = View::factory('cshi/reports/cshi_tp1_passport',$result)->render();
		}
/* ---------------------------------------------------------------------------------------------------------------------------------------- */

/* ------------------------------------- Код для отчетов по вагонным весам ---------------------------------- */
		public function action_cshireports_carts_passport()
		{
			$styles = array('css/calendar/aqua/theme.css'=>'screen',
			                'css/table.css'=>'screen',
			                'css/print.css'=>'print');
			$scripts = array('js/calendar/calendar-setup.js','js/calendar/lang/calendar-ru.js','js/calendar/calendar.js','js/calendar/checkDate.js');
			$this->template->styles = $styles;
			$this->template->scripts = $scripts;

			$data['menutitle']="Паспорта вагонов. Отчёты ЦШИ";

			$dt=new DateTime(date(''));
			$tmp_params = explode('&', $this->request->param('id','default'));
			foreach($tmp_params as $key => $value)
			{
				$tmp_param = explode('=', $value);
				$params[$tmp_param[0]]=$tmp_param[1];
			}
			$dt_end = (isset($params['dt_end']))?$params['dt_end']:$dt->format('d.m.Y H:i');
//			$dt_end = (isset($params['dt_end']))?$params['dt_end']:$dt->format('Y.m.d H:i');
			$dt->sub(new DateInterval('PT40H'));
			$dt_beg = (isset($params['dt_beg']))?$params['dt_beg']:$dt->format('d.m.Y H:i');
//			$dt_beg = (isset($params['dt_beg']))?$params['dt_beg']:$dt->format('Y.m.d H:i');
			$dt_beg=str_replace('-','.',$dt_beg);
			$dt_end=str_replace('-','.',$dt_end);

			$report = new Model_Cshicartsreports();
			$result = $report->GetTruckList($dt_beg,$dt_end, $params['product_type']);

			foreach ($result['data'] as &$value) {
				//$value['button']='<input type = "submit" value = "Паспорт" onclick = "javascript:location.href = /ASUTP/localmenu/cshireports_tp1_passport_result/'.str_replace(".","-",$value['DT_dry_beg']).'">';
				$value['Basa_NumTS']='<a href=/ASUTP/localmenu/cshireports_carts_passport_result/dt='.str_replace(" ","%20",str_replace(".","-",$value['Basa_Datetime_first'])).'>'.$value['Basa_NumTS'].'</a>';
			}
			if (strtotime($dt_beg)>strtotime($dt_end)) {
				$error_data=array('error_topic'=>'Ошибка!','error_message'=>'Начальная дата больше конечной!');
				$data['table'] = View::factory('/error_message',$error_data)->render();
			}
			else if (count($result['data'])!=0) {
				$table = Table::factory($result['data']);
				$table->set_attributes('id', 'report_table');
				$table->set_column_titles($result['header']);
				$table->set_footer($result['footer']);
				$data['table'] = $table->render();
			}
			else {
				$error_data=array('error_topic'=>'Ошибка!','error_message'=>'Нет данных за указанный период времени!');
				$data['table'] = View::factory('/error_message',$error_data)->render();
			}
			$data['dt_beg'] = $dt_beg;
			$data['dt_end'] = $dt_end;
			$data['product_type'] = $params['product_type'];

			$this->template->title = $data['menutitle'];
			$this->template->menu = View::factory('menu/main_menu',$data)->render();
			$this->template->local_menu = View::factory('menu/cshi_reports_menu')->render();
			$this->template->content = View::factory('cshi/reports/cshi_carts',$data)->render();
		}

		public function action_cshireports_carts_passport_result()
		{
			$styles = array('css/passport_table.css'=>'screen',
							'css/table.css'=>'screen',
			                'css/print.css'=>'print');
			$this->template->styles = $styles;

			$data['menutitle']="Паспорта вагонов. Отчёты ЦШИ";
			$tmp_params = explode('&', $this->request->param('id','default'));
			foreach($tmp_params as $key => $value)
			{
				$tmp_param = explode('=', $value);
				$params[$tmp_param[0]]=$tmp_param[1];
			}
			$dt = (isset($params['dt']))?$params['dt']:'';
			$report = new Model_Cshicartsreports();
			$result = $report->GetPassport($dt);

			$this->template->title = $data['menutitle'];
			$this->template->menu = View::factory('menu/main_menu',$data)->render();
			$this->template->local_menu = View::factory('menu/cshi_reports_menu')->render();
			$this->template->content = View::factory('cshi/reports/cshi_carts_passport',$result)->render();
		}

//Для авторизации через пользователя в базе
		public function action_cshireports_carts_avtorization()
		{
			$data['menutitle']="Форма авторизации пользователя. Отчёты ЦШИ";
			$this->template->title = $data['menutitle'];
			$this->template->menu = View::factory('menu/main_menu',$data)->render();
			$this->template->local_menu = View::factory('menu/cshi_reports_menu')->render();
			$this->template->content = View::factory('cshi/reports/avtorization',$data)->render();
		}

		public function action_cshireports_conveyer_carts_passport()
		{
			$styles = array('css/calendar/aqua/theme.css'=>'screen',
			                'css/table.css'=>'screen',
			                'css/print.css'=>'print');
			$scripts = array('js/calendar/calendar-setup.js','js/calendar/lang/calendar-ru.js','js/calendar/calendar.js','js/calendar/checkDate.js');
			$this->template->styles = $styles;
			$this->template->scripts = $scripts;

			$data['menutitle']="Архив технологических параметров. Отчёты ЦШИ";

			$dt=new DateTime(date(''));
			$tmp_params = explode('&', $this->request->param('id','default'));
			foreach($tmp_params as $key => $value)
			{
				$tmp_param = explode('=', $value);
				$params[$tmp_param[0]]=$tmp_param[1];
			}
			$dt_end = (isset($params['dt_end']))?$params['dt_end']:$dt->format('d.m.Y H:i');
//			$dt_end = (isset($params['dt_end']))?$params['dt_end']:$dt->format('Y.m.d H:i');
			$dt->sub(new DateInterval('PT40H'));
			$dt_beg = (isset($params['dt_beg']))?$params['dt_beg']:$dt->format('d.m.Y H:i');
//			$dt_beg = (isset($params['dt_beg']))?$params['dt_beg']:$dt->format('Y.m.d H:i');
			$dt_beg=str_replace('-','.',$dt_beg);
			$dt_end=str_replace('-','.',$dt_end);

			$report = new Model_Cshicartsreports();
			$result = $report->GetTruckList($dt_beg,$dt_end, $params['param_type']);


			if (strtotime($dt_beg)>strtotime($dt_end)) {
				$error_data=array('error_topic'=>'Ошибка!','error_message'=>'Начальная дата больше конечной!');
				$data['table'] = View::factory('/error_message',$error_data)->render();
			}
			else if (count($result['data'])!=0) {
				$table = Table::factory($result['data']);
				$table->set_attributes('id', 'report_table');
				$table->set_column_titles($result['header']);
				$table->set_footer($result['footer']);
				$data['table'] = $table->render();
			}
			else {
				$error_data=array('error_topic'=>'Ошибка!','error_message'=>'Нет данных за указанный период времени!');
				$data['table'] = View::factory('/error_message',$error_data)->render();
			}
			$data['dt_beg'] = $dt_beg;
			$data['dt_end'] = $dt_end;
			$data['param_type'] = $params['param_type'];

			$this->template->title = $data['menutitle'];
			$this->template->menu = View::factory('menu/main_menu',$data)->render();
			$this->template->local_menu = View::factory('menu/cshi_reports_menu')->render();
			$this->template->content = View::factory('cshi/reports/cshi_conveyer_carts',$data)->render();
		}
		//------------------------------------------------------------------------------
		public function action_brigade_trend_result()
		{
			$styles = array('css/print.css'=>'print');
			$this->template->styles = $styles;
			$tmp_params = explode('&', $this->request->param('id','default'));
			foreach($tmp_params as $key => $value)
			{
				$tmp_param = explode('=', $value);
				$params[$tmp_param[0]]=$tmp_param[1];
			}
			$date1 = (isset($params['date1']))?$params['date1']:date('Y-m-d');
			$date2 = (isset($params['date2']))?$params['date2']:date('Y-m-d');
			$brigade = (isset($params['brigade']))?$params['brigade']:0;
			$furnace = (isset($params['furnace']))?$params['furnace']:0;
			$date_begin = new DateTime($date1);
			$date_end = new DateTime($date2);
			$data['menutitle']='Система бригадных учетов';
			$localmenu='menu/cshi_reports_menu';
			$this->template->title =$data['menutitle'];
			$data['Begintime']=$date_begin->format('Y-m-d%20H:i:s');
			$data['Endtime']=$date_end->format('Y-m-d%20H:i:s');
			$data['Width']=955;
			$data['Height']=800;
			$data['brigade']=$brigade;
			$data['furnace']=$furnace;
			$data['note']="График составлен на период с ".$date_begin->format('d.m.Y')." по ".((strtotime($date_end->format('d.m.Y'))<strtotime("now"))?$date_end->format('d.m.Y'):date('d.m.Y'));
			$data['img']="/ASUTP/php/brigade_trend_img.php?a=".urlencode(join("&",array($data['Begintime'],$data['Endtime'],$data['brigade'],$data['furnace'])));
			$data['img2']="/ASUTP/php/brigade_trend_img2.php?a=".urlencode(join("&",array($data['Begintime'],$data['Endtime'],$data['brigade'],$data['furnace'])));
			$content_view=View::factory('trend_brigade',$data)->render();
			$this->template->menu = View::factory('menu/main_menu',$data)->render();
			$this->template->local_menu = View::factory($localmenu)->render();
			$this->template->content = $content_view;

		}
	}
?>
