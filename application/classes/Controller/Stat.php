<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Stat extends Controller_Tmp
{


    public function action_index()
    {
        $this->redirect('/stat/unapproved');
    }

    public function action_ipra()
    {
        $id = $this->request->param('id');
        if(empty($id)) {
            $page = View::factory('lpu/ipra');
            $page->medorg_change = true;
            $menu = Model_Catalog::GetMedOrg(true);
            $page->med_org = $menu;
            $session = Session::instance();
            $search = $session->get('search_string',false);
            if(!empty($search)) $search = json_decode($search,true);
            $page->search = $search;
            $sort = $session->get('sort_string',false);
            $page->$sort = $sort;
        }else{
            $page = View::factory('lpu/ipra_edit');
            $page->id = $id;
            $page->typeid = Model_Ipra::GetPersonsIpraTypeId($id);

        }
        $page->toolbar_cfg = View::factory('stat/toolbar');
        $this->page = $page;
    }
    public function action_ipraunassoc()
    {
        $page = View::factory('lpu/unassoc');

        $this->page = $page;
    }

    public function action_upload()
    {
        if ($this->request->method() == Request::POST) {
            $csv = file_get_contents($_FILES['tfoms']['tmp_name']);
            if(!empty($csv)){
                $by_line = explode("\n",$csv);
                if(!empty($by_line)){
                    foreach($by_line as $row){
                        if(!empty($row)) {
                            $columns = explode(';', $row);
                            $med_org = Model_Catalog::GetOneMedOrgBySMO($columns[7]);
                            $person = Model_Ipra::GetOnePerson((int)$columns[0]);
                            if(!empty($person)){
                                if(!empty($med_org)){
                                    Model_Ipra::AssocPerson($person['id'], $med_org['dicid']);
                                }else{
                                    if(Model_Catalog::GetOneForeignMedOrgBySMO($columns[7])){
                                        Model_Ipra::ForeignPerson($person['id']);
                                    }
                                }
                            }

                        }
                    }
                }
            }
        }
        if ($this->request->param('id') == 'detail') {
            $page = View::factory('stat/upload/detail');
            $page->person = Model_Ipra::GetOnePerson($this->request->query('id'));
            if ($page->person['gndr'] == '1') $page->person['gndr'] = 'Мужской';
            if ($page->person['gndr'] == '2') $page->person['gndr'] = 'Женский';
            if ($page->person['prg'] == '1') $page->person['prg'] = 'ИПР';
            if ($page->person['prg'] == '2') $page->person['prg'] = 'ПРП';
        } else {
            $page = View::factory('stat/upload/index');
            $page->med_org = Model_Catalog::GetMedOrg();
        }
        $page->toolbar_cfg = View::factory('stat/toolbar');
        $this->page = $page;
    }

    public function action_hot()
    {
        $page = View::factory('stat/hot/index');
        $menu = Model_Catalog::GetMedOrg(true);
        $page->med_org = $menu;
        $page->toolbar_cfg = View::factory('stat/toolbar');
        $this->page = $page;
    }

    public function action_foreign()
    {
        $page = View::factory('stat/foreign/index');
        $menu = Model_Catalog::GetMedOrg(true);
        $page->med_org = $menu;
        $page->toolbar_cfg = View::factory('stat/toolbar');
        $this->page = $page;
    }

    public function action_unassoc()
    {
        $page = View::factory('stat/unassoc/index');
        $menu = Model_Catalog::GetMedOrg(true);
        $page->med_org = $menu;
        $page->toolbar_cfg = View::factory('stat/toolbar');
        $this->page = $page;
    }


    public function action_unapproved()
    {
        $page = View::factory('stat/unapproved/index');
        $menu = Model_Ipra::GetReadyIpraMedOrgCountedUnApproved();
        foreach($menu as $key=>$one)
            if(empty($one['recid'])) unset($menu[$key]);
        $page->med_org = $menu;
        $page->toolbar_cfg = View::factory('stat/toolbar');
        $this->page = $page;
    }

    public function action_approved()
    {
        $page = View::factory('stat/approved/index');
        $menu = Model_Ipra::GetReadyIpraMedOrgCountedApproved();
        if(!empty($menu))
        foreach($menu as $key=>$one)
            if(empty($one['recid'])) unset($menu[$key]);
        $page->med_org = $menu;
        $page->toolbar_cfg = View::factory('stat/toolbar');
        $this->page = $page;
    }

    public function action_medorgcount()
    {
        $from = $this->request->query('from');
        $to = $this->request->query('to');
        $page = View::factory('stat/medorgcount/index');
        $page->from = $from;
        $page->to = $to;
        $page->med_org = Model_Catalog::GetMedOrg();
        $page->toolbar_cfg = View::factory('stat/toolbar');
        $this->page = $page;
    }

    public function action_medorg()
    {
        $page = View::factory('stat/medorg/index');
        $page->toolbar_cfg = View::factory('stat/toolbar');
        $this->page = $page;
    }
    public function action_outgoing()
    {
        $page = View::factory('stat/outgoing/index');
        $page->toolbar_cfg = View::factory('stat/toolbar');
//        $medorg = Model_Catalog::GetOneMedOrg($this->user['med_org_id']);
//        if(!empty($medorg['name'])) $page->medorg_name = trim($medorg['name']);
//        else $page->medorg_name = '(не определено)';
        $this->page = $page;
    }


}
