<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                   ATTENTION!
 * If you see this message in your browser (Internet Explorer, Mozilla Firefox, Google Chrome, etc.)
 * this means that PHP is not properly installed on your web server. Please refer to the PHP manual
 * for more details: http://php.net/manual/install.php 
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */

    include_once dirname(__FILE__) . '/components/startup.php';
    include_once dirname(__FILE__) . '/components/application.php';


    include_once dirname(__FILE__) . '/' . 'database_engine/mysql_engine.php';
    include_once dirname(__FILE__) . '/' . 'components/page/page.php';
    include_once dirname(__FILE__) . '/' . 'components/page/detail_page.php';
    include_once dirname(__FILE__) . '/' . 'components/page/nested_form_page.php';
    include_once dirname(__FILE__) . '/' . 'authorization.php';

    function GetConnectionOptions()
    {
        $result = GetGlobalConnectionOptions();
        $result['client_encoding'] = 'utf8';
        GetApplication()->GetUserAuthentication()->applyIdentityToConnectionOptions($result);
        return $result;
    }

    
    
    
    
    // OnBeforePageExecute event handler
    
    
    
    class Query_Radio_Response_Query_Gallery_with_Response_and_RequestPage extends DetailPage
    {
        protected function DoBeforeCreate()
        {
            $selectQuery = 'SELECT RRG.*, RResp.ResponseDateTime, RResp.ResponseDescription, RReq.PatientId, RReq.RequestDateTime, RReq.RequestDescription FROM radioresponsegallery AS RRG  
            INNER JOIN radioresponse AS RResp ON RRG.ResponseId = RResp.Id 
            INNER JOIN radiorequests AS RReq  ON RResp.RequestId = RReq.Id 
            WHERE RResp.STATUS = \'reponse\'';
            $insertQuery = array('INSERT INTO radioresponsegallery (
            `ResponseId` ,
            `ResponseGalleryImage` ,
            `ResponseGalleryDescription` ,
            `InsertID` ,
            `InsertIP` ,
            `InsertTime`
            )
            VALUES ( :ResponseId, :ResponseGalleryImage, :ResponseGalleryDescription, :InsertID, :InsertIP, :InsertTime )');
            $updateQuery = array('UPDATE radioresponsegallery SET  
            `ResponseId` = :ResponseId ,
            `ResponseGalleryImage` = :ResponseGalleryImage ,
            `ResponseGalleryDescription` = :ResponseGalleryDescription ,
            `UpdateID` = :UpdateID,
            `UpdateIP` = :UpdateIP ,
            `UpdateTime` = :UpdateTime
            WHERE Id = :Id');
            $deleteQuery = array('DELETE FROM radioresponsegallery WHERE Id = :Id');
            $this->dataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Gallery with Response and Request');
            $field = new StringField('Id');
            $this->dataset->AddField($field, true);
            $field = new StringField('ResponseId');
            $this->dataset->AddField($field, false);
            $field = new StringField('ResponseGalleryImage');
            $this->dataset->AddField($field, false);
            $field = new StringField('ResponseGalleryDescription');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('UpdateID');
            $this->dataset->AddField($field, false);
            $field = new StringField('UpdateIP');
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('UpdateTime');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('InsertID');
            $this->dataset->AddField($field, false);
            $field = new StringField('InsertIP');
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('InsertTime');
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('ResponseDateTime');
            $this->dataset->AddField($field, false);
            $field = new StringField('ResponseDescription');
            $this->dataset->AddField($field, false);
            $field = new StringField('PatientId');
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('RequestDateTime');
            $this->dataset->AddField($field, false);
            $field = new StringField('RequestDescription');
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('ResponseId', '(SELECT TResp.*,  
            TReq.RequestUserId,  
            TReq.PatientId , 
            TReq.RequestDateTime , 
            TReq.RequestDescription , 
            TReq.Status AS RequestStatus , 
            CONCAT(TReq.Id, \' (\', TReq.RequestDateTime,\')\') AS IPlusTime 
            FROM radioresponse AS TResp 
            INNER JOIN radiorequests AS TReq ON TReq.Id = TResp.RequestId)', new StringField('Id'), new StringField('IPlusTime', 'ResponseId_IPlusTime', 'ResponseId_IPlusTime_Query Radio Response'), 'ResponseId_IPlusTime_Query Radio Response');
            $this->dataset->AddLookupField('PatientId', '(SELECT P.*, CONCAT(P.PatientName, \' (\', P.PatientAge,\')\') AS NamePlusAge FROM patients AS P)', new StringField('PatientId'), new StringField('NamePlusAge', 'PatientId_NamePlusAge', 'PatientId_NamePlusAge_Query Patient Details'), 'PatientId_NamePlusAge_Query Patient Details');
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new PageNavigator('pnav', $this, $this->dataset);
            $partitionNavigator->SetRowsPerPage(20);
            $result->AddPageNavigator($partitionNavigator);
            
            return $result;
        }
    
        protected function CreateRssGenerator()
        {
            return null;
        }
    
        protected function setupCharts()
        {
    
        }
    
        protected function getFiltersColumns()
        {
            return array(
                new FilterColumn($this->dataset, 'Id', 'Id', 'Id'),
                new FilterColumn($this->dataset, 'ResponseId', 'ResponseId_IPlusTime', 'Rapport Id'),
                new FilterColumn($this->dataset, 'ResponseGalleryImage', 'ResponseGalleryImage', 'Image'),
                new FilterColumn($this->dataset, 'ResponseGalleryDescription', 'ResponseGalleryDescription', 'Description'),
                new FilterColumn($this->dataset, 'UpdateID', 'UpdateID', 'Update ID'),
                new FilterColumn($this->dataset, 'UpdateIP', 'UpdateIP', 'Update IP'),
                new FilterColumn($this->dataset, 'UpdateTime', 'UpdateTime', 'Update Time'),
                new FilterColumn($this->dataset, 'InsertID', 'InsertID', 'Insert ID'),
                new FilterColumn($this->dataset, 'InsertIP', 'InsertIP', 'Insert IP'),
                new FilterColumn($this->dataset, 'InsertTime', 'InsertTime', 'Insert Time'),
                new FilterColumn($this->dataset, 'ResponseDateTime', 'ResponseDateTime', 'Date du rapport'),
                new FilterColumn($this->dataset, 'ResponseDescription', 'ResponseDescription', 'Rapport'),
                new FilterColumn($this->dataset, 'PatientId', 'PatientId_NamePlusAge', 'Patient'),
                new FilterColumn($this->dataset, 'RequestDateTime', 'RequestDateTime', 'Date de la demande'),
                new FilterColumn($this->dataset, 'RequestDescription', 'RequestDescription', 'Demande')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['Id'])
                ->addColumn($columns['ResponseId'])
                ->addColumn($columns['ResponseGalleryImage'])
                ->addColumn($columns['ResponseGalleryDescription'])
                ->addColumn($columns['UpdateID'])
                ->addColumn($columns['UpdateIP'])
                ->addColumn($columns['UpdateTime'])
                ->addColumn($columns['InsertID'])
                ->addColumn($columns['InsertIP'])
                ->addColumn($columns['InsertTime']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
    
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('id_edit');
            
            $filterBuilder->addColumn(
                $columns['Id'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new AutocompleteComboBox('responseid_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_ResponseId_IPlusTime_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('ResponseId', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_ResponseId_IPlusTime_search');
            
            $text_editor = new TextEdit('ResponseId');
            
            $filterBuilder->addColumn(
                $columns['ResponseId'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('ResponseGalleryImage');
            
            $filterBuilder->addColumn(
                $columns['ResponseGalleryImage'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('responsegallerydescription_edit');
            
            $filterBuilder->addColumn(
                $columns['ResponseGalleryDescription'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new SpinEdit('updateid_edit');
            
            $filterBuilder->addColumn(
                $columns['UpdateID'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('updateip_edit');
            
            $filterBuilder->addColumn(
                $columns['UpdateIP'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new DateTimeEdit('updatetime_edit', false, 'Y-m-d H:i:s');
            
            $filterBuilder->addColumn(
                $columns['UpdateTime'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::DATE_EQUALS => $main_editor,
                    FilterConditionOperator::DATE_DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::TODAY => null,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new SpinEdit('insertid_edit');
            
            $filterBuilder->addColumn(
                $columns['InsertID'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('insertip_edit');
            
            $filterBuilder->addColumn(
                $columns['InsertIP'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new DateTimeEdit('inserttime_edit', false, 'Y-m-d H:i:s');
            
            $filterBuilder->addColumn(
                $columns['InsertTime'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::DATE_EQUALS => $main_editor,
                    FilterConditionOperator::DATE_DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::TODAY => null,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
            $actions = $grid->getActions();
            $actions->setCaption($this->GetLocalizerCaptions()->GetMessageString('Actions'));
            $actions->setPosition(ActionList::POSITION_LEFT);
            
            if ($this->GetSecurityInfo()->HasViewGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('View'), OPERATION_VIEW, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
            
            if ($this->GetSecurityInfo()->HasEditGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Edit'), OPERATION_EDIT, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowEditButtonHandler', $this);
            }
            
            if ($this->GetSecurityInfo()->HasDeleteGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Delete'), OPERATION_DELETE, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowDeleteButtonHandler', $this);
                $operation->SetAdditionalAttribute('data-modal-operation', 'delete');
                $operation->SetAdditionalAttribute('data-delete-handler-name', $this->GetModalGridDeleteHandler());
            }
            
            if ($this->GetSecurityInfo()->HasAddGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Copy'), OPERATION_COPY, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            //
            // View column for Id field
            //
            $column = new TextViewColumn('Id', 'Id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ResponseGalleryImage field
            //
            $column = new ExternalImageViewColumn('ResponseGalleryImage', 'ResponseGalleryImage', 'Image', $this->dataset);
            $column->setImageHintTemplate('');
            $column->setNullLabel('');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $column->setHeight('100');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ResponseGalleryDescription field
            //
            $column = new TextViewColumn('ResponseGalleryDescription', 'ResponseGalleryDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for Id field
            //
            $column = new TextViewColumn('Id', 'Id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for IPlusTime field
            //
            $column = new TextViewColumn('ResponseId', 'ResponseId_IPlusTime', 'Rapport Id', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ResponseGalleryImage field
            //
            $column = new ExternalImageViewColumn('ResponseGalleryImage', 'ResponseGalleryImage', 'Image', $this->dataset);
            $column->setImageHintTemplate('');
            $column->setNullLabel('');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $column->setHeight('100');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ResponseGalleryDescription field
            //
            $column = new TextViewColumn('ResponseGalleryDescription', 'ResponseGalleryDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for UpdateID field
            //
            $column = new NumberViewColumn('UpdateID', 'UpdateID', 'Update ID', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for UpdateIP field
            //
            $column = new TextViewColumn('UpdateIP', 'UpdateIP', 'Update IP', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for UpdateTime field
            //
            $column = new DateTimeViewColumn('UpdateTime', 'UpdateTime', 'Update Time', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for InsertID field
            //
            $column = new NumberViewColumn('InsertID', 'InsertID', 'Insert ID', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for InsertIP field
            //
            $column = new TextViewColumn('InsertIP', 'InsertIP', 'Insert IP', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for InsertTime field
            //
            $column = new DateTimeViewColumn('InsertTime', 'InsertTime', 'Insert Time', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ResponseDateTime field
            //
            $column = new DateTimeViewColumn('ResponseDateTime', 'ResponseDateTime', 'Date du rapport', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ResponseDescription field
            //
            $column = new TextViewColumn('ResponseDescription', 'ResponseDescription', 'Rapport', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridQuery Radio Response.Query Gallery with Response and Request_ResponseDescription_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for NamePlusAge field
            //
            $column = new TextViewColumn('PatientId', 'PatientId_NamePlusAge', 'Patient', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for RequestDateTime field
            //
            $column = new DateTimeViewColumn('RequestDateTime', 'RequestDateTime', 'Date de la demande', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestDescription', 'RequestDescription', 'Demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridQuery Radio Response.Query Gallery with Response and Request_RequestDescription_handler_view');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for ResponseId field
            //
            $editor = new AutocompleteComboBox('responseid_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $selectQuery = 'SELECT TResp.*,  
            TReq.RequestUserId,  
            TReq.PatientId , 
            TReq.RequestDateTime , 
            TReq.RequestDescription , 
            TReq.Status AS RequestStatus , 
            CONCAT(TReq.Id, \' (\', TReq.RequestDateTime,\')\') AS IPlusTime 
            FROM radioresponse AS TResp 
            INNER JOIN radiorequests AS TReq ON TReq.Id = TResp.RequestId';
            $insertQuery = array('INSERT INTO radioresponse (
            `ResponseUserId` ,
            `RequestId` ,
            `ResponseDateTime` ,
            `ResponseImage`,
            `ResponseDescription` ,
            `Conclusion` ,
            `Type` ,
            `Hauteur` ,
            `Largeur` ,
            `Resolution` ,
            `Status`,
            `UpdateID` ,
            `UpdateIP` ,
            `UpdateTime` ,
            `InsertID` ,
            `InsertIP` ,
            `InsertTime`
            )
            VALUES (
            :ResponseUserId, :RequestId, :ResponseDateTime, :ResponseImage, :ResponseDescription, :Conclusion, :Type, :Hauteur, :Largeur, :Resolution, :Status, :InsertID, :InsertIP, :InsertTime, :InsertID, :InsertIP, :InsertTime
            )');
            $updateQuery = array('UPDATE radioresponse SET  
            `ResponseUserId` = :ResponseUserId ,
            `RequestId` = :RequestId ,
            `ResponseDateTime` = :ResponseDateTime ,
            `ResponseImage` = :ResponseImage,
            `ResponseDescription` = :ResponseDescription,
            `Conclusion` = :Conclusion,
            `Type` = :Type,
            `Hauteur` = :Hauteur,
            `Largeur` = :Largeur,
            `Resolution` = :Resolution,
            `Status` = :Status,
            `UpdateID` = :UpdateID,
            `UpdateIP` = :UpdateIP ,
            `UpdateTime` = :UpdateTime
            WHERE Id = :Id');
            $deleteQuery = array('DELETE FROM radioresponse  
            WHERE Id = :Id');
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Radio Response');
            $field = new StringField('Id');
            $lookupDataset->AddField($field, true);
            $field = new StringField('ResponseUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseImage');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('ResponseDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Conclusion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Type');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Hauteur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Largeur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Resolution');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Status');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('UpdateID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UpdateIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('UpdateTime');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('InsertID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('InsertIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('InsertTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('PatientId');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('RequestDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestStatus');
            $lookupDataset->AddField($field, false);
            $field = new StringField('IPlusTime');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('IPlusTime', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Rapport Id', 'ResponseId', 'ResponseId_IPlusTime', 'edit_ResponseId_IPlusTime_search', $editor, $this->dataset, $lookupDataset, 'Id', 'IPlusTime', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for ResponseGalleryImage field
            //
            $editor = new ImageUploader('responsegalleryimage_edit');
            $editor->SetShowImage(true);
            $editor->setAcceptableFileTypes('image/*');
            $editColumn = new UploadFileToFolderColumn('Image', 'ResponseGalleryImage', $editor, $this->dataset, false, false, 'patients/patient_%PatientId%/radio_%ResponseId%', '%original_file_name%', $this->OnGetCustomUploadFilename, false);
            $editColumn->SetReplaceUploadedFileIfExist(true);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for ResponseGalleryDescription field
            //
            $editor = new TextEdit('responsegallerydescription_edit');
            $editColumn = new CustomEditColumn('Description', 'ResponseGalleryDescription', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for ResponseId field
            //
            $editor = new AutocompleteComboBox('responseid_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $selectQuery = 'SELECT TResp.*,  
            TReq.RequestUserId,  
            TReq.PatientId , 
            TReq.RequestDateTime , 
            TReq.RequestDescription , 
            TReq.Status AS RequestStatus , 
            CONCAT(TReq.Id, \' (\', TReq.RequestDateTime,\')\') AS IPlusTime 
            FROM radioresponse AS TResp 
            INNER JOIN radiorequests AS TReq ON TReq.Id = TResp.RequestId';
            $insertQuery = array('INSERT INTO radioresponse (
            `ResponseUserId` ,
            `RequestId` ,
            `ResponseDateTime` ,
            `ResponseImage`,
            `ResponseDescription` ,
            `Conclusion` ,
            `Type` ,
            `Hauteur` ,
            `Largeur` ,
            `Resolution` ,
            `Status`,
            `UpdateID` ,
            `UpdateIP` ,
            `UpdateTime` ,
            `InsertID` ,
            `InsertIP` ,
            `InsertTime`
            )
            VALUES (
            :ResponseUserId, :RequestId, :ResponseDateTime, :ResponseImage, :ResponseDescription, :Conclusion, :Type, :Hauteur, :Largeur, :Resolution, :Status, :InsertID, :InsertIP, :InsertTime, :InsertID, :InsertIP, :InsertTime
            )');
            $updateQuery = array('UPDATE radioresponse SET  
            `ResponseUserId` = :ResponseUserId ,
            `RequestId` = :RequestId ,
            `ResponseDateTime` = :ResponseDateTime ,
            `ResponseImage` = :ResponseImage,
            `ResponseDescription` = :ResponseDescription,
            `Conclusion` = :Conclusion,
            `Type` = :Type,
            `Hauteur` = :Hauteur,
            `Largeur` = :Largeur,
            `Resolution` = :Resolution,
            `Status` = :Status,
            `UpdateID` = :UpdateID,
            `UpdateIP` = :UpdateIP ,
            `UpdateTime` = :UpdateTime
            WHERE Id = :Id');
            $deleteQuery = array('DELETE FROM radioresponse  
            WHERE Id = :Id');
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Radio Response');
            $field = new StringField('Id');
            $lookupDataset->AddField($field, true);
            $field = new StringField('ResponseUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseImage');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('ResponseDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Conclusion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Type');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Hauteur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Largeur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Resolution');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Status');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('UpdateID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UpdateIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('UpdateTime');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('InsertID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('InsertIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('InsertTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('PatientId');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('RequestDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestStatus');
            $lookupDataset->AddField($field, false);
            $field = new StringField('IPlusTime');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('IPlusTime', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Rapport Id', 'ResponseId', 'ResponseId_IPlusTime', 'insert_ResponseId_IPlusTime_search', $editor, $this->dataset, $lookupDataset, 'Id', 'IPlusTime', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for ResponseGalleryImage field
            //
            $editor = new ImageUploader('responsegalleryimage_edit');
            $editor->SetShowImage(true);
            $editor->setAcceptableFileTypes('image/*');
            $editColumn = new UploadFileToFolderColumn('Image', 'ResponseGalleryImage', $editor, $this->dataset, false, false, 'patients/patient_%PatientId%/radio_%ResponseId%', '%original_file_name%', $this->OnGetCustomUploadFilename, false);
            $editColumn->SetReplaceUploadedFileIfExist(true);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for ResponseGalleryDescription field
            //
            $editor = new TextEdit('responsegallerydescription_edit');
            $editColumn = new CustomEditColumn('Description', 'ResponseGalleryDescription', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for Id field
            //
            $column = new TextViewColumn('Id', 'Id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for ResponseGalleryImage field
            //
            $column = new ExternalImageViewColumn('ResponseGalleryImage', 'ResponseGalleryImage', 'Image', $this->dataset);
            $column->setImageHintTemplate('');
            $column->setNullLabel('');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $column->setHeight('100');
            $grid->AddPrintColumn($column);
            
            //
            // View column for ResponseGalleryDescription field
            //
            $column = new TextViewColumn('ResponseGalleryDescription', 'ResponseGalleryDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for Id field
            //
            $column = new TextViewColumn('Id', 'Id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for ResponseGalleryImage field
            //
            $column = new ExternalImageViewColumn('ResponseGalleryImage', 'ResponseGalleryImage', 'Image', $this->dataset);
            $column->setImageHintTemplate('');
            $column->setNullLabel('');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $column->setHeight('100');
            $grid->AddExportColumn($column);
            
            //
            // View column for ResponseGalleryDescription field
            //
            $column = new TextViewColumn('ResponseGalleryDescription', 'ResponseGalleryDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for Id field
            //
            $column = new TextViewColumn('Id', 'Id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for IPlusTime field
            //
            $column = new TextViewColumn('ResponseId', 'ResponseId_IPlusTime', 'Rapport Id', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for ResponseGalleryImage field
            //
            $column = new ExternalImageViewColumn('ResponseGalleryImage', 'ResponseGalleryImage', 'Image', $this->dataset);
            $column->setImageHintTemplate('');
            $column->setNullLabel('');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $column->setHeight('100');
            $grid->AddCompareColumn($column);
        }
    
        private function AddCompareHeaderColumns(Grid $grid)
        {
    
        }
    
        public function GetPageDirection()
        {
            return null;
        }
    
        public function isFilterConditionRequired()
        {
            return false;
        }
    
        protected function ApplyCommonColumnEditProperties(CustomEditColumn $column)
        {
            $column->SetDisplaySetToNullCheckBox(false);
            $column->SetDisplaySetToDefaultCheckBox(false);
    		$column->SetVariableContainer($this->GetColumnVariableContainer());
        }
    
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
        protected function GetEnableModalGridDelete() { return true; }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset);
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(true);
            else
               $result->SetAllowDeleteSelected(false);   
            
            ApplyCommonPageSettings($this, $result);
            
            $result->SetUseImagesForActions(true);
            $result->SetUseFixedHeader(false);
            $result->SetShowLineNumbers(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(true);
            $result->setAllowCompare(true);
            $this->AddCompareHeaderColumns($result);
            $this->AddCompareColumns($result);
            $result->setTableBordered(false);
            $result->setTableCondensed(false);
            
            $result->SetHighlightRowAtHover(false);
            $result->SetWidth('');
            $this->AddOperationsColumns($result);
            $this->AddFieldColumns($result);
            $this->AddSingleRecordViewColumns($result);
            $this->AddEditColumns($result);
            $this->AddInsertColumns($result);
            $this->AddPrintColumns($result);
            $this->AddExportColumns($result);
    
    
            $this->SetShowPageList(true);
            $this->SetShowTopPageNavigator(true);
            $this->SetShowBottomPageNavigator(true);
            $this->setPrintListAvailable(true);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(true);
            $this->setExportListAvailable(array('excel','word','pdf'));
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array('excel','word','pdf'));
            $this->setDescription('Images des films du rapport en cours');
            $this->setDetailedDescription('Images des films du rapport en cours');
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            $selectQuery = 'SELECT TResp.*,  
            TReq.RequestUserId,  
            TReq.PatientId , 
            TReq.RequestDateTime , 
            TReq.RequestDescription , 
            TReq.Status AS RequestStatus , 
            CONCAT(TReq.Id, \' (\', TReq.RequestDateTime,\')\') AS IPlusTime 
            FROM radioresponse AS TResp 
            INNER JOIN radiorequests AS TReq ON TReq.Id = TResp.RequestId';
            $insertQuery = array('INSERT INTO radioresponse (
            `ResponseUserId` ,
            `RequestId` ,
            `ResponseDateTime` ,
            `ResponseImage`,
            `ResponseDescription` ,
            `Conclusion` ,
            `Type` ,
            `Hauteur` ,
            `Largeur` ,
            `Resolution` ,
            `Status`,
            `UpdateID` ,
            `UpdateIP` ,
            `UpdateTime` ,
            `InsertID` ,
            `InsertIP` ,
            `InsertTime`
            )
            VALUES (
            :ResponseUserId, :RequestId, :ResponseDateTime, :ResponseImage, :ResponseDescription, :Conclusion, :Type, :Hauteur, :Largeur, :Resolution, :Status, :InsertID, :InsertIP, :InsertTime, :InsertID, :InsertIP, :InsertTime
            )');
            $updateQuery = array('UPDATE radioresponse SET  
            `ResponseUserId` = :ResponseUserId ,
            `RequestId` = :RequestId ,
            `ResponseDateTime` = :ResponseDateTime ,
            `ResponseImage` = :ResponseImage,
            `ResponseDescription` = :ResponseDescription,
            `Conclusion` = :Conclusion,
            `Type` = :Type,
            `Hauteur` = :Hauteur,
            `Largeur` = :Largeur,
            `Resolution` = :Resolution,
            `Status` = :Status,
            `UpdateID` = :UpdateID,
            `UpdateIP` = :UpdateIP ,
            `UpdateTime` = :UpdateTime
            WHERE Id = :Id');
            $deleteQuery = array('DELETE FROM radioresponse  
            WHERE Id = :Id');
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Radio Response');
            $field = new StringField('Id');
            $lookupDataset->AddField($field, true);
            $field = new StringField('ResponseUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseImage');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('ResponseDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Conclusion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Type');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Hauteur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Largeur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Resolution');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Status');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('UpdateID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UpdateIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('UpdateTime');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('InsertID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('InsertIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('InsertTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('PatientId');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('RequestDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestStatus');
            $lookupDataset->AddField($field, false);
            $field = new StringField('IPlusTime');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('IPlusTime', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_ResponseId_IPlusTime_search', 'Id', 'IPlusTime', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            $selectQuery = 'SELECT TResp.*,  
            TReq.RequestUserId,  
            TReq.PatientId , 
            TReq.RequestDateTime , 
            TReq.RequestDescription , 
            TReq.Status AS RequestStatus , 
            CONCAT(TReq.Id, \' (\', TReq.RequestDateTime,\')\') AS IPlusTime 
            FROM radioresponse AS TResp 
            INNER JOIN radiorequests AS TReq ON TReq.Id = TResp.RequestId';
            $insertQuery = array('INSERT INTO radioresponse (
            `ResponseUserId` ,
            `RequestId` ,
            `ResponseDateTime` ,
            `ResponseImage`,
            `ResponseDescription` ,
            `Conclusion` ,
            `Type` ,
            `Hauteur` ,
            `Largeur` ,
            `Resolution` ,
            `Status`,
            `UpdateID` ,
            `UpdateIP` ,
            `UpdateTime` ,
            `InsertID` ,
            `InsertIP` ,
            `InsertTime`
            )
            VALUES (
            :ResponseUserId, :RequestId, :ResponseDateTime, :ResponseImage, :ResponseDescription, :Conclusion, :Type, :Hauteur, :Largeur, :Resolution, :Status, :InsertID, :InsertIP, :InsertTime, :InsertID, :InsertIP, :InsertTime
            )');
            $updateQuery = array('UPDATE radioresponse SET  
            `ResponseUserId` = :ResponseUserId ,
            `RequestId` = :RequestId ,
            `ResponseDateTime` = :ResponseDateTime ,
            `ResponseImage` = :ResponseImage,
            `ResponseDescription` = :ResponseDescription,
            `Conclusion` = :Conclusion,
            `Type` = :Type,
            `Hauteur` = :Hauteur,
            `Largeur` = :Largeur,
            `Resolution` = :Resolution,
            `Status` = :Status,
            `UpdateID` = :UpdateID,
            `UpdateIP` = :UpdateIP ,
            `UpdateTime` = :UpdateTime
            WHERE Id = :Id');
            $deleteQuery = array('DELETE FROM radioresponse  
            WHERE Id = :Id');
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Radio Response');
            $field = new StringField('Id');
            $lookupDataset->AddField($field, true);
            $field = new StringField('ResponseUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseImage');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('ResponseDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Conclusion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Type');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Hauteur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Largeur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Resolution');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Status');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('UpdateID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UpdateIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('UpdateTime');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('InsertID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('InsertIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('InsertTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('PatientId');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('RequestDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestStatus');
            $lookupDataset->AddField($field, false);
            $field = new StringField('IPlusTime');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('IPlusTime', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_ResponseId_IPlusTime_search', 'Id', 'IPlusTime', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $selectQuery = 'SELECT TResp.*,  
            TReq.RequestUserId,  
            TReq.PatientId , 
            TReq.RequestDateTime , 
            TReq.RequestDescription , 
            TReq.Status AS RequestStatus , 
            CONCAT(TReq.Id, \' (\', TReq.RequestDateTime,\')\') AS IPlusTime 
            FROM radioresponse AS TResp 
            INNER JOIN radiorequests AS TReq ON TReq.Id = TResp.RequestId';
            $insertQuery = array('INSERT INTO radioresponse (
            `ResponseUserId` ,
            `RequestId` ,
            `ResponseDateTime` ,
            `ResponseImage`,
            `ResponseDescription` ,
            `Conclusion` ,
            `Type` ,
            `Hauteur` ,
            `Largeur` ,
            `Resolution` ,
            `Status`,
            `UpdateID` ,
            `UpdateIP` ,
            `UpdateTime` ,
            `InsertID` ,
            `InsertIP` ,
            `InsertTime`
            )
            VALUES (
            :ResponseUserId, :RequestId, :ResponseDateTime, :ResponseImage, :ResponseDescription, :Conclusion, :Type, :Hauteur, :Largeur, :Resolution, :Status, :InsertID, :InsertIP, :InsertTime, :InsertID, :InsertIP, :InsertTime
            )');
            $updateQuery = array('UPDATE radioresponse SET  
            `ResponseUserId` = :ResponseUserId ,
            `RequestId` = :RequestId ,
            `ResponseDateTime` = :ResponseDateTime ,
            `ResponseImage` = :ResponseImage,
            `ResponseDescription` = :ResponseDescription,
            `Conclusion` = :Conclusion,
            `Type` = :Type,
            `Hauteur` = :Hauteur,
            `Largeur` = :Largeur,
            `Resolution` = :Resolution,
            `Status` = :Status,
            `UpdateID` = :UpdateID,
            `UpdateIP` = :UpdateIP ,
            `UpdateTime` = :UpdateTime
            WHERE Id = :Id');
            $deleteQuery = array('DELETE FROM radioresponse  
            WHERE Id = :Id');
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Radio Response');
            $field = new StringField('Id');
            $lookupDataset->AddField($field, true);
            $field = new StringField('ResponseUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseImage');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('ResponseDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Conclusion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Type');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Hauteur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Largeur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Resolution');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Status');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('UpdateID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UpdateIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('UpdateTime');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('InsertID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('InsertIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('InsertTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('PatientId');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('RequestDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestStatus');
            $lookupDataset->AddField($field, false);
            $field = new StringField('IPlusTime');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('IPlusTime', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_ResponseId_IPlusTime_search', 'Id', 'IPlusTime', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for ResponseDescription field
            //
            $column = new TextViewColumn('ResponseDescription', 'ResponseDescription', 'Rapport', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridQuery Radio Response.Query Gallery with Response and Request_ResponseDescription_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestDescription', 'RequestDescription', 'Demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridQuery Radio Response.Query Gallery with Response and Request_RequestDescription_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);$selectQuery = 'SELECT TResp.*,  
            TReq.RequestUserId,  
            TReq.PatientId , 
            TReq.RequestDateTime , 
            TReq.RequestDescription , 
            TReq.Status AS RequestStatus , 
            CONCAT(TReq.Id, \' (\', TReq.RequestDateTime,\')\') AS IPlusTime 
            FROM radioresponse AS TResp 
            INNER JOIN radiorequests AS TReq ON TReq.Id = TResp.RequestId';
            $insertQuery = array('INSERT INTO radioresponse (
            `ResponseUserId` ,
            `RequestId` ,
            `ResponseDateTime` ,
            `ResponseImage`,
            `ResponseDescription` ,
            `Conclusion` ,
            `Type` ,
            `Hauteur` ,
            `Largeur` ,
            `Resolution` ,
            `Status`,
            `UpdateID` ,
            `UpdateIP` ,
            `UpdateTime` ,
            `InsertID` ,
            `InsertIP` ,
            `InsertTime`
            )
            VALUES (
            :ResponseUserId, :RequestId, :ResponseDateTime, :ResponseImage, :ResponseDescription, :Conclusion, :Type, :Hauteur, :Largeur, :Resolution, :Status, :InsertID, :InsertIP, :InsertTime, :InsertID, :InsertIP, :InsertTime
            )');
            $updateQuery = array('UPDATE radioresponse SET  
            `ResponseUserId` = :ResponseUserId ,
            `RequestId` = :RequestId ,
            `ResponseDateTime` = :ResponseDateTime ,
            `ResponseImage` = :ResponseImage,
            `ResponseDescription` = :ResponseDescription,
            `Conclusion` = :Conclusion,
            `Type` = :Type,
            `Hauteur` = :Hauteur,
            `Largeur` = :Largeur,
            `Resolution` = :Resolution,
            `Status` = :Status,
            `UpdateID` = :UpdateID,
            `UpdateIP` = :UpdateIP ,
            `UpdateTime` = :UpdateTime
            WHERE Id = :Id');
            $deleteQuery = array('DELETE FROM radioresponse  
            WHERE Id = :Id');
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Radio Response');
            $field = new StringField('Id');
            $lookupDataset->AddField($field, true);
            $field = new StringField('ResponseUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseImage');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('ResponseDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Conclusion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Type');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Hauteur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Largeur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Resolution');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Status');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('UpdateID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UpdateIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('UpdateTime');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('InsertID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('InsertIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('InsertTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('PatientId');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('RequestDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestStatus');
            $lookupDataset->AddField($field, false);
            $field = new StringField('IPlusTime');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('IPlusTime', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_ResponseId_IPlusTime_search', 'Id', 'IPlusTime', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
        }
       
        protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderPrintColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderExportColumn($exportType, $fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomDrawRow($rowData, &$cellFontColor, &$cellFontSize, &$cellBgColor, &$cellItalicAttr, &$cellBoldAttr)
        {
    
        }
    
        protected function doExtendedCustomDrawRow($rowData, &$rowCellStyles, &$rowStyles, &$rowClasses, &$cellClasses)
        {
    
        }
    
        protected function doCustomRenderTotal($totalValue, $aggregate, $columnName, &$customText, &$handled)
        {
    
        }
    
        protected function doCustomCompareColumn($columnName, $valueA, $valueB, &$result)
        {
    
        }
    
        protected function doBeforeInsertRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
            $rowData['InsertIP'] = $_SERVER['SERVER_ADDR'];
            $rowData['InsertTime'] = SMDateTime::Now();
            $rowData['InsertID'] = $page->GetCurrentUserId();
        }
    
        protected function doBeforeUpdateRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
            $rowData['UpdateIP'] = $_SERVER['SERVER_ADDR'];
            $rowData['UpdateTime'] = SMDateTime::Now();
            $rowData['UpdateID'] = $page->GetCurrentUserId();
            /*
            //Traitement Nom du fichier
            $filname = $rowData['ResponseGalleryImage'];
            $oldfilename = $filname;  
            $array_filname = explode("/",$filname);
            $lastrow = count($array_filname)-1;
            $extExploded = explode(".",$array_filname[$lastrow]);
            $extension = $extExploded[1]; 
            $lastNewImplded = "Film_gallery" . "_PAT" . $rowData['PatientId'] . "_RAD". $rowData['ResponseId'] . "_GAL".$rowData['Id'] . "." . $extension;
            $array_filname[$lastrow] = $lastNewImplded;
            
            $filname = implode("/",$array_filname);
            
            //MAJ du Nom du fichier
            $rowData['ResponseGalleryImage'] = $filname.'<||>'.$oldfilename;
            */
        }
    
        protected function doBeforeDeleteRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
            /*
            //GetLastInsertId()
            
            
            //Traitement Nom du fichier
            $filname = $rowData['ResponseGalleryImage'];
            $oldfilename = $filname;  
            $array_filname = explode("/",$filname);
            $lastrow = count($array_filname)-1;
            $extExploded = explode(".",$array_filname[$lastrow]);
            $extension = $extExploded[1]; 
            $lastNewImplded = "Film_gallery" . "_PAT" . $rowData['PatientId'] . "_RAD". $rowData['ResponseId'] . "_GAL".$rowData['Id'] . "." . $extension;
            $array_filname[$lastrow] = $lastNewImplded;
            
            $filname = implode("/",$array_filname);
            
            //MAJ du Nom du fichier
            $rowData['ResponseGalleryImage'] = $filname;
            rename($filname, $oldfilename);
            */
        }
    
        protected function doAfterUpdateRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
            /*
            //Traitement Nom du fichier
            $filname = $rowData['ResponseGalleryImage'];
            $oldfilename = $filname;  
            $array_filname = explode("/",$filname);
            $lastrow = count($array_filname)-1;
            $extExploded = explode(".",$array_filname[$lastrow]);
            $extension = $extExploded[1]; 
            $lastNewImplded = "Film_gallery" . "_PAT" . $rowData['PatientId'] . "_RAD". $rowData['ResponseId'] . "_GAL".$rowData['Id'] . "." . $extension;
            $array_filname[$lastrow] = $lastNewImplded;
            
            $filname = implode("/",$array_filname);
            
            //MAJ du Nom du fichier
            //$rowData['ResponseGalleryImage'] = $filname;
            //rename($filname, $oldfilename);
            $filenameArr = explode('<||>',$rowData['ResponseGalleryImage']);
            rename($filnameArr[0], $oldfilenameArr[1]);
            */
        }
    
        protected function doAfterDeleteRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doCustomHTMLHeader($page, &$customHtmlHeaderText)
        { 
    
        }
    
        protected function doGetCustomTemplate($type, $part, $mode, &$result, &$params)
        {
    
        }
    
        protected function doGetCustomExportOptions(Page $page, $exportType, $rowData, &$options)
        {
    
        }
    
        protected function doGetCustomUploadFileName($fieldName, $rowData, &$result, &$handled, $originalFileName, $originalFileExtension, $fileSize)
        {
            if ($fieldName === 'ResponseGalleryImage') {
               
            
                $newFileName = "Film_gallery_" . date('Y-m-d_H-i-s') ."_PAT" . $rowData['PatientId'] ."_RAD". $rowData['ResponseId'] . "." . $originalFileExtension;
            
                $result = str_replace($originalFileName, $newFileName, $result);
            
                $handled = true;
            
            }
        }
    
        protected function doPrepareChart(Chart $chart)
        {
    
        }
    
        protected function doPageLoaded()
        {
    
        }
    
        protected function doGetCustomPagePermissions(Page $page, PermissionSet &$permissions, &$handled)
        {
    
        }
    
        protected function doGetCustomRecordPermissions(Page $page, &$usingCondition, $rowData, &$allowEdit, &$allowDelete, &$mergeWithDefault, &$handled)
        {
    
        }
    
    }
    
    // OnBeforePageExecute event handler
    
    
    
    class Query_Radio_ResponsePage extends Page
    {
        protected function DoBeforeCreate()
        {
            $selectQuery = 'SELECT TResp.*,  
            TReq.RequestUserId,  
            TReq.PatientId , 
            TReq.RequestDateTime , 
            TReq.RequestDescription , 
            TReq.Status AS RequestStatus , 
            CONCAT(TReq.Id, \' (\', TReq.RequestDateTime,\')\') AS IPlusTime 
            FROM radioresponse AS TResp 
            INNER JOIN radiorequests AS TReq ON TReq.Id = TResp.RequestId';
            $insertQuery = array('INSERT INTO radioresponse (
            `ResponseUserId` ,
            `RequestId` ,
            `ResponseDateTime` ,
            `ResponseImage`,
            `ResponseDescription` ,
            `Conclusion` ,
            `Type` ,
            `Hauteur` ,
            `Largeur` ,
            `Resolution` ,
            `Status`,
            `UpdateID` ,
            `UpdateIP` ,
            `UpdateTime` ,
            `InsertID` ,
            `InsertIP` ,
            `InsertTime`
            )
            VALUES (
            :ResponseUserId, :RequestId, :ResponseDateTime, :ResponseImage, :ResponseDescription, :Conclusion, :Type, :Hauteur, :Largeur, :Resolution, :Status, :InsertID, :InsertIP, :InsertTime, :InsertID, :InsertIP, :InsertTime
            )');
            $updateQuery = array('UPDATE radioresponse SET  
            `ResponseUserId` = :ResponseUserId ,
            `RequestId` = :RequestId ,
            `ResponseDateTime` = :ResponseDateTime ,
            `ResponseImage` = :ResponseImage,
            `ResponseDescription` = :ResponseDescription,
            `Conclusion` = :Conclusion,
            `Type` = :Type,
            `Hauteur` = :Hauteur,
            `Largeur` = :Largeur,
            `Resolution` = :Resolution,
            `Status` = :Status,
            `UpdateID` = :UpdateID,
            `UpdateIP` = :UpdateIP ,
            `UpdateTime` = :UpdateTime
            WHERE Id = :Id');
            $deleteQuery = array('DELETE FROM radioresponse  
            WHERE Id = :Id');
            $this->dataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Radio Response');
            $field = new StringField('Id');
            $this->dataset->AddField($field, true);
            $field = new StringField('ResponseUserId');
            $this->dataset->AddField($field, false);
            $field = new StringField('RequestId');
            $this->dataset->AddField($field, false);
            $field = new StringField('ResponseImage');
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('ResponseDateTime');
            $this->dataset->AddField($field, false);
            $field = new StringField('ResponseDescription');
            $this->dataset->AddField($field, false);
            $field = new StringField('Conclusion');
            $this->dataset->AddField($field, false);
            $field = new StringField('Type');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('Hauteur');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('Largeur');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('Resolution');
            $this->dataset->AddField($field, false);
            $field = new StringField('Status');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('UpdateID');
            $this->dataset->AddField($field, false);
            $field = new StringField('UpdateIP');
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('UpdateTime');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('InsertID');
            $this->dataset->AddField($field, false);
            $field = new StringField('InsertIP');
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('InsertTime');
            $this->dataset->AddField($field, false);
            $field = new StringField('RequestUserId');
            $this->dataset->AddField($field, false);
            $field = new StringField('PatientId');
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('RequestDateTime');
            $this->dataset->AddField($field, false);
            $field = new StringField('RequestDescription');
            $this->dataset->AddField($field, false);
            $field = new StringField('RequestStatus');
            $this->dataset->AddField($field, false);
            $field = new StringField('IPlusTime');
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('ResponseUserId', '(SELECT * FROM users WHERE (Roles LIKE \'%Manipulateur%\') AND userStatus = \'0\')', new StringField('UserId'), new StringField('UserName', 'ResponseUserId_UserName', 'ResponseUserId_UserName_Query Manipulateurs'), 'ResponseUserId_UserName_Query Manipulateurs');
            $this->dataset->AddLookupField('RequestId', 'radiorequests', new IntegerField('Id', null, null, true), new IntegerField('Id', 'RequestId_Id', 'RequestId_Id_radiorequests', true), 'RequestId_Id_radiorequests');
            $this->dataset->AddLookupField('RequestUserId', '(SELECT * FROM users WHERE (Roles LIKE \'%Demandeur%\') AND userStatus = \'0\')', new StringField('UserId'), new StringField('UserName', 'RequestUserId_UserName', 'RequestUserId_UserName_Query Demandeurs'), 'RequestUserId_UserName_Query Demandeurs');
            $this->dataset->AddLookupField('PatientId', '(SELECT P.*, CONCAT(P.PatientName, \' (\', P.PatientAge,\')\') AS NamePlusAge FROM patients AS P)', new StringField('PatientId'), new StringField('NamePlusAge', 'PatientId_NamePlusAge', 'PatientId_NamePlusAge_Query Patient Details'), 'PatientId_NamePlusAge_Query Patient Details');
            $this->dataset->AddLookupField('Type', 'radiotypes', new IntegerField('Id', null, null, true), new StringField('Type', 'Type_Type', 'Type_Type_radiotypes'), 'Type_Type_radiotypes');
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new PageNavigator('pnav', $this, $this->dataset);
            $partitionNavigator->SetRowsPerPage(20);
            $result->AddPageNavigator($partitionNavigator);
            
            return $result;
        }
    
        protected function CreateRssGenerator()
        {
            return null;
        }
    
        protected function setupCharts()
        {
    
        }
    
        protected function getFiltersColumns()
        {
            return array(
                new FilterColumn($this->dataset, 'Id', 'Id', 'Id'),
                new FilterColumn($this->dataset, 'ResponseUserId', 'ResponseUserId_UserName', 'Manipulateur'),
                new FilterColumn($this->dataset, 'RequestId', 'RequestId_Id', 'Demande'),
                new FilterColumn($this->dataset, 'RequestUserId', 'RequestUserId_UserName', 'Demandeur'),
                new FilterColumn($this->dataset, 'PatientId', 'PatientId_NamePlusAge', 'Patient'),
                new FilterColumn($this->dataset, 'RequestDateTime', 'RequestDateTime', 'Date de demande'),
                new FilterColumn($this->dataset, 'RequestDescription', 'RequestDescription', 'Objet de la demande'),
                new FilterColumn($this->dataset, 'ResponseImage', 'ResponseImage', 'Image'),
                new FilterColumn($this->dataset, 'ResponseDateTime', 'ResponseDateTime', 'Date de reponse'),
                new FilterColumn($this->dataset, 'ResponseDescription', 'ResponseDescription', 'Response'),
                new FilterColumn($this->dataset, 'Conclusion', 'Conclusion', 'Conclusion'),
                new FilterColumn($this->dataset, 'Type', 'Type_Type', 'Type'),
                new FilterColumn($this->dataset, 'Hauteur', 'Hauteur', 'Hauteur'),
                new FilterColumn($this->dataset, 'Largeur', 'Largeur', 'Largeur'),
                new FilterColumn($this->dataset, 'Resolution', 'Resolution', 'Resolution'),
                new FilterColumn($this->dataset, 'RequestStatus', 'RequestStatus', 'Etat de la demande'),
                new FilterColumn($this->dataset, 'Status', 'Status', 'Etat de la reponse'),
                new FilterColumn($this->dataset, 'UpdateID', 'UpdateID', 'Update ID'),
                new FilterColumn($this->dataset, 'UpdateIP', 'UpdateIP', 'Update IP'),
                new FilterColumn($this->dataset, 'UpdateTime', 'UpdateTime', 'Update Time'),
                new FilterColumn($this->dataset, 'InsertID', 'InsertID', 'Insert ID'),
                new FilterColumn($this->dataset, 'InsertIP', 'InsertIP', 'Insert IP'),
                new FilterColumn($this->dataset, 'InsertTime', 'InsertTime', 'Insert Time'),
                new FilterColumn($this->dataset, 'IPlusTime', 'IPlusTime', 'IPlus Time')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['Id'])
                ->addColumn($columns['ResponseUserId'])
                ->addColumn($columns['RequestId'])
                ->addColumn($columns['RequestUserId'])
                ->addColumn($columns['PatientId'])
                ->addColumn($columns['RequestDateTime'])
                ->addColumn($columns['RequestDescription'])
                ->addColumn($columns['ResponseImage'])
                ->addColumn($columns['ResponseDateTime'])
                ->addColumn($columns['ResponseDescription'])
                ->addColumn($columns['Conclusion'])
                ->addColumn($columns['Type'])
                ->addColumn($columns['Hauteur'])
                ->addColumn($columns['Largeur'])
                ->addColumn($columns['Resolution'])
                ->addColumn($columns['Status'])
                ->addColumn($columns['IPlusTime']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('ResponseUserId')
                ->setOptionsFor('RequestId')
                ->setOptionsFor('RequestUserId')
                ->setOptionsFor('PatientId')
                ->setOptionsFor('RequestDateTime')
                ->setOptionsFor('ResponseDateTime')
                ->setOptionsFor('Type');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('id_edit');
            
            $filterBuilder->addColumn(
                $columns['Id'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new AutocompleteComboBox('responseuserid_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_ResponseUserId_UserName_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('ResponseUserId', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_ResponseUserId_UserName_search');
            
            $text_editor = new TextEdit('ResponseUserId');
            
            $filterBuilder->addColumn(
                $columns['ResponseUserId'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new AutocompleteComboBox('requestid_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_RequestId_Id_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('RequestId', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_RequestId_Id_search');
            
            $text_editor = new TextEdit('RequestId');
            
            $filterBuilder->addColumn(
                $columns['RequestId'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new AutocompleteComboBox('requestuserid_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_RequestUserId_UserName_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('RequestUserId', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_RequestUserId_UserName_search');
            
            $text_editor = new TextEdit('RequestUserId');
            
            $filterBuilder->addColumn(
                $columns['RequestUserId'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new AutocompleteComboBox('patientid_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_PatientId_NamePlusAge_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('PatientId', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_PatientId_NamePlusAge_search');
            
            $text_editor = new TextEdit('PatientId');
            
            $filterBuilder->addColumn(
                $columns['PatientId'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new DateTimeEdit('requestdatetime_edit', false, 'Y-m-d H:i:s');
            
            $filterBuilder->addColumn(
                $columns['RequestDateTime'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::DATE_EQUALS => $main_editor,
                    FilterConditionOperator::DATE_DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::TODAY => null,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('RequestDescription');
            
            $filterBuilder->addColumn(
                $columns['RequestDescription'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('ResponseImage');
            
            $filterBuilder->addColumn(
                $columns['ResponseImage'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new DateTimeEdit('responsedatetime_edit', false, 'Y-m-d H:i:s');
            
            $filterBuilder->addColumn(
                $columns['ResponseDateTime'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::DATE_EQUALS => $main_editor,
                    FilterConditionOperator::DATE_DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::TODAY => null,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('ResponseDescription');
            
            $filterBuilder->addColumn(
                $columns['ResponseDescription'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('Conclusion');
            
            $filterBuilder->addColumn(
                $columns['Conclusion'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new AutocompleteComboBox('type_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_Type_Type_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('Type', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_Type_Type_search');
            
            $text_editor = new TextEdit('Type');
            
            $filterBuilder->addColumn(
                $columns['Type'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('hauteur_edit');
            
            $filterBuilder->addColumn(
                $columns['Hauteur'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('largeur_edit');
            
            $filterBuilder->addColumn(
                $columns['Largeur'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('resolution_edit');
            
            $filterBuilder->addColumn(
                $columns['Resolution'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new ComboBox('status_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $main_editor->addChoice('demande', 'demande');
            $main_editor->addChoice('encours', 'en cours');
            $main_editor->addChoice('reponse', 'reponse');
            $main_editor->addChoice('annule', 'annulé');
            $main_editor->SetAllowNullValue(false);
            
            $multi_value_select_editor = new MultiValueSelect('Status');
            $multi_value_select_editor->setChoices($main_editor->getChoices());
            
            $text_editor = new TextEdit('Status');
            
            $filterBuilder->addColumn(
                $columns['Status'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('iplustime_edit');
            
            $filterBuilder->addColumn(
                $columns['IPlusTime'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
            $actions = $grid->getActions();
            $actions->setCaption($this->GetLocalizerCaptions()->GetMessageString('Actions'));
            $actions->setPosition(ActionList::POSITION_LEFT);
            
            if ($this->GetSecurityInfo()->HasViewGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('View'), OPERATION_VIEW, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
            
            if ($this->GetSecurityInfo()->HasEditGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Edit'), OPERATION_EDIT, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowEditButtonHandler', $this);
            }
            
            if ($this->GetSecurityInfo()->HasDeleteGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Delete'), OPERATION_DELETE, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowDeleteButtonHandler', $this);
                $operation->SetAdditionalAttribute('data-modal-operation', 'delete');
                $operation->SetAdditionalAttribute('data-delete-handler-name', $this->GetModalGridDeleteHandler());
            }
            
            if ($this->GetSecurityInfo()->HasAddGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Copy'), OPERATION_COPY, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            if (GetCurrentUserPermissionSetForDataSource('Query Radio Response.Query Gallery with Response and Request')->HasViewGrant() && $withDetails)
            {
            //
            // View column for Query_Radio_Response_Query_Gallery_with_Response_and_Request detail
            //
            $column = new DetailColumn(array('Id'), 'Query Radio Response.Query Gallery with Response and Request', 'Query_Radio_Response_Query_Gallery_with_Response_and_Request_handler', $this->dataset, 'Album films');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            }
            
            //
            // View column for Id field
            //
            $column = new TextViewColumn('Id', 'Id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('ResponseUserId', 'ResponseUserId_UserName', 'Manipulateur', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Id field
            //
            $column = new NumberViewColumn('RequestId', 'RequestId_Id', 'Demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('RequestUserId', 'RequestUserId_UserName', 'Demandeur', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for NamePlusAge field
            //
            $column = new TextViewColumn('PatientId', 'PatientId_NamePlusAge', 'Patient', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for RequestDateTime field
            //
            $column = new DateTimeViewColumn('RequestDateTime', 'RequestDateTime', 'Date de demande', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestDescription', 'RequestDescription', 'Objet de la demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('Query_Radio_ResponseGrid_RequestDescription_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ResponseImage field
            //
            $column = new ExternalImageViewColumn('ResponseImage', 'ResponseImage', 'Image', $this->dataset);
            $column->setImageHintTemplate('');
            $column->setNullLabel('');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $column->setHrefTemplate('%ResponseImage%');
            $column->setTarget('" data-fancybox="gal');
            $column->setHeight('100');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ResponseDateTime field
            //
            $column = new DateTimeViewColumn('ResponseDateTime', 'ResponseDateTime', 'Date de reponse', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ResponseDescription field
            //
            $column = new TextViewColumn('ResponseDescription', 'ResponseDescription', 'Response', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('Query_Radio_ResponseGrid_ResponseDescription_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Conclusion field
            //
            $column = new TextViewColumn('Conclusion', 'Conclusion', 'Conclusion', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('Query_Radio_ResponseGrid_Conclusion_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Type field
            //
            $column = new TextViewColumn('Type', 'Type_Type', 'Type', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Hauteur field
            //
            $column = new NumberViewColumn('Hauteur', 'Hauteur', 'Hauteur', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Largeur field
            //
            $column = new NumberViewColumn('Largeur', 'Largeur', 'Largeur', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Resolution field
            //
            $column = new NumberViewColumn('Resolution', 'Resolution', 'Resolution', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Status field
            //
            $column = new TextViewColumn('Status', 'Status', 'Etat de la reponse', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for IPlusTime field
            //
            $column = new TextViewColumn('IPlusTime', 'IPlusTime', 'IPlus Time', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for Id field
            //
            $column = new TextViewColumn('Id', 'Id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('ResponseUserId', 'ResponseUserId_UserName', 'Manipulateur', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Id field
            //
            $column = new NumberViewColumn('RequestId', 'RequestId_Id', 'Demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('RequestUserId', 'RequestUserId_UserName', 'Demandeur', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for NamePlusAge field
            //
            $column = new TextViewColumn('PatientId', 'PatientId_NamePlusAge', 'Patient', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for RequestDateTime field
            //
            $column = new DateTimeViewColumn('RequestDateTime', 'RequestDateTime', 'Date de demande', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestDescription', 'RequestDescription', 'Objet de la demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('Query_Radio_ResponseGrid_RequestDescription_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ResponseImage field
            //
            $column = new ExternalImageViewColumn('ResponseImage', 'ResponseImage', 'Image', $this->dataset);
            $column->setImageHintTemplate('');
            $column->setNullLabel('');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $column->setHrefTemplate('%ResponseImage%');
            $column->setTarget('" data-fancybox="gal');
            $column->setHeight('100');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ResponseDateTime field
            //
            $column = new DateTimeViewColumn('ResponseDateTime', 'ResponseDateTime', 'Date de reponse', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ResponseDescription field
            //
            $column = new TextViewColumn('ResponseDescription', 'ResponseDescription', 'Response', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('Query_Radio_ResponseGrid_ResponseDescription_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Conclusion field
            //
            $column = new TextViewColumn('Conclusion', 'Conclusion', 'Conclusion', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('Query_Radio_ResponseGrid_Conclusion_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Type field
            //
            $column = new TextViewColumn('Type', 'Type_Type', 'Type', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Hauteur field
            //
            $column = new NumberViewColumn('Hauteur', 'Hauteur', 'Hauteur', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Largeur field
            //
            $column = new NumberViewColumn('Largeur', 'Largeur', 'Largeur', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Resolution field
            //
            $column = new NumberViewColumn('Resolution', 'Resolution', 'Resolution', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Status field
            //
            $column = new TextViewColumn('Status', 'Status', 'Etat de la reponse', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for UpdateID field
            //
            $column = new NumberViewColumn('UpdateID', 'UpdateID', 'Update ID', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for UpdateIP field
            //
            $column = new TextViewColumn('UpdateIP', 'UpdateIP', 'Update IP', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for UpdateTime field
            //
            $column = new DateTimeViewColumn('UpdateTime', 'UpdateTime', 'Update Time', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for InsertID field
            //
            $column = new NumberViewColumn('InsertID', 'InsertID', 'Insert ID', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for InsertIP field
            //
            $column = new TextViewColumn('InsertIP', 'InsertIP', 'Insert IP', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for InsertTime field
            //
            $column = new DateTimeViewColumn('InsertTime', 'InsertTime', 'Insert Time', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for IPlusTime field
            //
            $column = new TextViewColumn('IPlusTime', 'IPlusTime', 'IPlus Time', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for Id field
            //
            $editor = new TextEdit('id_edit');
            $editColumn = new CustomEditColumn('Id', 'Id', $editor, $this->dataset);
            $editColumn->setVisible(false);
            $editColumn->setEnabled(false);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for ResponseUserId field
            //
            $editor = new AutocompleteComboBox('responseuserid_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $selectQuery = 'SELECT * FROM users WHERE (Roles LIKE \'%Manipulateur%\') AND userStatus = \'0\'';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Manipulateurs');
            $field = new StringField('UserId');
            $lookupDataset->AddField($field, true);
            $field = new StringField('UserName');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserPassword');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserFullName');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Mail');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Token');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserSecretQestion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserSecretAnswer');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserStatus');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Roles');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('UserName', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Manipulateur', 'ResponseUserId', 'ResponseUserId_UserName', 'edit_ResponseUserId_UserName_search', $editor, $this->dataset, $lookupDataset, 'UserId', 'UserName', '');
            $editColumn->setVisible(false);
            $editColumn->setEnabled(false);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for RequestId field
            //
            $editor = new AutocompleteComboBox('requestid_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`radiorequests`');
            $field = new IntegerField('Id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new IntegerField('RequestUserId');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('PatientId');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('RequestDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Status');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('UpdateID');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('UpdateIP');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('UpdateTime');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('InsertID');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('InsertIP');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('InsertTime');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('Id', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Demande', 'RequestId', 'RequestId_Id', 'edit_RequestId_Id_search', $editor, $this->dataset, $lookupDataset, 'Id', 'Id', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for RequestUserId field
            //
            $editor = new AutocompleteComboBox('requestuserid_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $selectQuery = 'SELECT * FROM users WHERE (Roles LIKE \'%Demandeur%\') AND userStatus = \'0\'';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Demandeurs');
            $field = new StringField('UserId');
            $lookupDataset->AddField($field, true);
            $field = new StringField('UserName');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserPassword');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserFullName');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Mail');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Token');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserSecretQestion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserSecretAnswer');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserStatus');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Roles');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('UserName', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Demandeur', 'RequestUserId', 'RequestUserId_UserName', 'edit_RequestUserId_UserName_search', $editor, $this->dataset, $lookupDataset, 'UserId', 'UserName', '');
            $editColumn->setEnabled(false);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for PatientId field
            //
            $editor = new AutocompleteComboBox('patientid_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $selectQuery = 'SELECT P.*, CONCAT(P.PatientName, \' (\', P.PatientAge,\')\') AS NamePlusAge FROM patients AS P';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Patient Details');
            $field = new StringField('PatientId');
            $lookupDataset->AddField($field, true);
            $field = new StringField('PatientName');
            $lookupDataset->AddField($field, false);
            $field = new DateField('PatientBirthday');
            $lookupDataset->AddField($field, false);
            $field = new StringField('PatientAge');
            $lookupDataset->AddField($field, false);
            $field = new StringField('PatientDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('NamePlusAge');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('NamePlusAge', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Patient', 'PatientId', 'PatientId_NamePlusAge', 'edit_PatientId_NamePlusAge_search', $editor, $this->dataset, $lookupDataset, 'PatientId', 'NamePlusAge', '');
            $editColumn->setEnabled(false);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for RequestDateTime field
            //
            $editor = new DateTimeEdit('requestdatetime_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Date de demande', 'RequestDateTime', $editor, $this->dataset);
            $editColumn->setEnabled(false);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for RequestDescription field
            //
            $editor = new TextAreaEdit('requestdescription_edit', 50, 8);
            $editColumn = new CustomEditColumn('Objet de la demande', 'RequestDescription', $editor, $this->dataset);
            $editColumn->setEnabled(false);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for ResponseImage field
            //
            $editor = new ImageUploader('responseimage_edit');
            $editor->SetShowImage(true);
            $editor->setAcceptableFileTypes('image/*');
            $editColumn = new UploadFileToFolderColumn('Image', 'ResponseImage', $editor, $this->dataset, false, false, 'films/', '%original_file_name%', $this->OnGetCustomUploadFilename, false);
            $editColumn->SetReplaceUploadedFileIfExist(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for ResponseDescription field
            //
            $editor = new HtmlWysiwygEditor('responsedescription_edit');
            $editColumn = new CustomEditColumn('Response', 'ResponseDescription', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Conclusion field
            //
            $editor = new HtmlWysiwygEditor('conclusion_edit');
            $editColumn = new CustomEditColumn('Conclusion', 'Conclusion', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Type field
            //
            $editor = new AutocompleteComboBox('type_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`radiotypes`');
            $field = new IntegerField('Id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('Type');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Height');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Width');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Resolution');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('Description');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('Type', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Type', 'Type', 'Type_Type', 'edit_Type_Type_search', $editor, $this->dataset, $lookupDataset, 'Id', 'Type', '');
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Hauteur field
            //
            $editor = new TextEdit('hauteur_edit');
            $editColumn = new CustomEditColumn('Hauteur', 'Hauteur', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Largeur field
            //
            $editor = new TextEdit('largeur_edit');
            $editColumn = new CustomEditColumn('Largeur', 'Largeur', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Resolution field
            //
            $editor = new TextEdit('resolution_edit');
            $editColumn = new CustomEditColumn('Resolution', 'Resolution', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Status field
            //
            $editor = new ComboBox('status_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice('demande', 'demande');
            $editor->addChoice('encours', 'en cours');
            $editor->addChoice('reponse', 'reponse');
            $editor->addChoice('annule', 'annulé');
            $editColumn = new CustomEditColumn('Etat de la reponse', 'Status', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for IPlusTime field
            //
            $editor = new TextEdit('iplustime_edit');
            $editColumn = new CustomEditColumn('IPlus Time', 'IPlusTime', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for ResponseUserId field
            //
            $editor = new AutocompleteComboBox('responseuserid_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $selectQuery = 'SELECT * FROM users WHERE (Roles LIKE \'%Manipulateur%\') AND userStatus = \'0\'';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Manipulateurs');
            $field = new StringField('UserId');
            $lookupDataset->AddField($field, true);
            $field = new StringField('UserName');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserPassword');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserFullName');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Mail');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Token');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserSecretQestion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserSecretAnswer');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserStatus');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Roles');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('UserName', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Manipulateur', 'ResponseUserId', 'ResponseUserId_UserName', 'insert_ResponseUserId_UserName_search', $editor, $this->dataset, $lookupDataset, 'UserId', 'UserName', '');
            $editColumn->setVisible(false);
            $editColumn->setEnabled(false);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for RequestId field
            //
            $editor = new AutocompleteComboBox('requestid_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`radiorequests`');
            $field = new IntegerField('Id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new IntegerField('RequestUserId');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('PatientId');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('RequestDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Status');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('UpdateID');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('UpdateIP');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('UpdateTime');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('InsertID');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('InsertIP');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('InsertTime');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('Id', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Demande', 'RequestId', 'RequestId_Id', 'insert_RequestId_Id_search', $editor, $this->dataset, $lookupDataset, 'Id', 'Id', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for RequestUserId field
            //
            $editor = new AutocompleteComboBox('requestuserid_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $selectQuery = 'SELECT * FROM users WHERE (Roles LIKE \'%Demandeur%\') AND userStatus = \'0\'';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Demandeurs');
            $field = new StringField('UserId');
            $lookupDataset->AddField($field, true);
            $field = new StringField('UserName');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserPassword');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserFullName');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Mail');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Token');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserSecretQestion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserSecretAnswer');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserStatus');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Roles');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('UserName', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Demandeur', 'RequestUserId', 'RequestUserId_UserName', 'insert_RequestUserId_UserName_search', $editor, $this->dataset, $lookupDataset, 'UserId', 'UserName', '');
            $editColumn->setEnabled(false);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for PatientId field
            //
            $editor = new AutocompleteComboBox('patientid_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $selectQuery = 'SELECT P.*, CONCAT(P.PatientName, \' (\', P.PatientAge,\')\') AS NamePlusAge FROM patients AS P';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Patient Details');
            $field = new StringField('PatientId');
            $lookupDataset->AddField($field, true);
            $field = new StringField('PatientName');
            $lookupDataset->AddField($field, false);
            $field = new DateField('PatientBirthday');
            $lookupDataset->AddField($field, false);
            $field = new StringField('PatientAge');
            $lookupDataset->AddField($field, false);
            $field = new StringField('PatientDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('NamePlusAge');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('NamePlusAge', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Patient', 'PatientId', 'PatientId_NamePlusAge', 'insert_PatientId_NamePlusAge_search', $editor, $this->dataset, $lookupDataset, 'PatientId', 'NamePlusAge', '');
            $editColumn->setEnabled(false);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for RequestDateTime field
            //
            $editor = new DateTimeEdit('requestdatetime_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Date de demande', 'RequestDateTime', $editor, $this->dataset);
            $editColumn->setEnabled(false);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for RequestDescription field
            //
            $editor = new TextAreaEdit('requestdescription_edit', 50, 8);
            $editColumn = new CustomEditColumn('Objet de la demande', 'RequestDescription', $editor, $this->dataset);
            $editColumn->setEnabled(false);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for ResponseImage field
            //
            $editor = new ImageUploader('responseimage_edit');
            $editor->SetShowImage(true);
            $editor->setAcceptableFileTypes('image/*');
            $editColumn = new UploadFileToFolderColumn('Image', 'ResponseImage', $editor, $this->dataset, false, false, 'films/', '%original_file_name%', $this->OnGetCustomUploadFilename, false);
            $editColumn->SetReplaceUploadedFileIfExist(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for ResponseDescription field
            //
            $editor = new HtmlWysiwygEditor('responsedescription_edit');
            $editColumn = new CustomEditColumn('Response', 'ResponseDescription', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for Conclusion field
            //
            $editor = new HtmlWysiwygEditor('conclusion_edit');
            $editColumn = new CustomEditColumn('Conclusion', 'Conclusion', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for Type field
            //
            $editor = new AutocompleteComboBox('type_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`radiotypes`');
            $field = new IntegerField('Id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('Type');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Height');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Width');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Resolution');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('Description');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('Type', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Type', 'Type', 'Type_Type', 'insert_Type_Type_search', $editor, $this->dataset, $lookupDataset, 'Id', 'Type', '');
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for Hauteur field
            //
            $editor = new TextEdit('hauteur_edit');
            $editColumn = new CustomEditColumn('Hauteur', 'Hauteur', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for Largeur field
            //
            $editor = new TextEdit('largeur_edit');
            $editColumn = new CustomEditColumn('Largeur', 'Largeur', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for Resolution field
            //
            $editor = new TextEdit('resolution_edit');
            $editColumn = new CustomEditColumn('Resolution', 'Resolution', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for Status field
            //
            $editor = new ComboBox('status_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice('demande', 'demande');
            $editor->addChoice('encours', 'en cours');
            $editor->addChoice('reponse', 'reponse');
            $editor->addChoice('annule', 'annulé');
            $editColumn = new CustomEditColumn('Etat de la reponse', 'Status', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for IPlusTime field
            //
            $editor = new TextEdit('iplustime_edit');
            $editColumn = new CustomEditColumn('IPlus Time', 'IPlusTime', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for Id field
            //
            $column = new TextViewColumn('Id', 'Id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('ResponseUserId', 'ResponseUserId_UserName', 'Manipulateur', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for Id field
            //
            $column = new NumberViewColumn('RequestId', 'RequestId_Id', 'Demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
            
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('RequestUserId', 'RequestUserId_UserName', 'Demandeur', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for NamePlusAge field
            //
            $column = new TextViewColumn('PatientId', 'PatientId_NamePlusAge', 'Patient', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for RequestDateTime field
            //
            $column = new DateTimeViewColumn('RequestDateTime', 'RequestDateTime', 'Date de demande', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestDescription', 'RequestDescription', 'Objet de la demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('Query_Radio_ResponseGrid_RequestDescription_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for ResponseImage field
            //
            $column = new ExternalImageViewColumn('ResponseImage', 'ResponseImage', 'Image', $this->dataset);
            $column->setImageHintTemplate('');
            $column->setNullLabel('');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $column->setHrefTemplate('%ResponseImage%');
            $column->setTarget('" data-fancybox="gal');
            $column->setHeight('100');
            $grid->AddPrintColumn($column);
            
            //
            // View column for ResponseDateTime field
            //
            $column = new DateTimeViewColumn('ResponseDateTime', 'ResponseDateTime', 'Date de reponse', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for ResponseDescription field
            //
            $column = new TextViewColumn('ResponseDescription', 'ResponseDescription', 'Response', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('Query_Radio_ResponseGrid_ResponseDescription_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Conclusion field
            //
            $column = new TextViewColumn('Conclusion', 'Conclusion', 'Conclusion', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('Query_Radio_ResponseGrid_Conclusion_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Type field
            //
            $column = new TextViewColumn('Type', 'Type_Type', 'Type', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for Hauteur field
            //
            $column = new NumberViewColumn('Hauteur', 'Hauteur', 'Hauteur', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Largeur field
            //
            $column = new NumberViewColumn('Largeur', 'Largeur', 'Largeur', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Resolution field
            //
            $column = new NumberViewColumn('Resolution', 'Resolution', 'Resolution', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Status field
            //
            $column = new TextViewColumn('Status', 'Status', 'Etat de la reponse', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for IPlusTime field
            //
            $column = new TextViewColumn('IPlusTime', 'IPlusTime', 'IPlus Time', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for Id field
            //
            $column = new TextViewColumn('Id', 'Id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('ResponseUserId', 'ResponseUserId_UserName', 'Manipulateur', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for Id field
            //
            $column = new NumberViewColumn('RequestId', 'RequestId_Id', 'Demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
            
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('RequestUserId', 'RequestUserId_UserName', 'Demandeur', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for NamePlusAge field
            //
            $column = new TextViewColumn('PatientId', 'PatientId_NamePlusAge', 'Patient', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for RequestDateTime field
            //
            $column = new DateTimeViewColumn('RequestDateTime', 'RequestDateTime', 'Date de demande', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestDescription', 'RequestDescription', 'Objet de la demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('Query_Radio_ResponseGrid_RequestDescription_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for ResponseImage field
            //
            $column = new ExternalImageViewColumn('ResponseImage', 'ResponseImage', 'Image', $this->dataset);
            $column->setImageHintTemplate('');
            $column->setNullLabel('');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $column->setHrefTemplate('%ResponseImage%');
            $column->setTarget('" data-fancybox="gal');
            $column->setHeight('100');
            $grid->AddExportColumn($column);
            
            //
            // View column for ResponseDateTime field
            //
            $column = new DateTimeViewColumn('ResponseDateTime', 'ResponseDateTime', 'Date de reponse', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for ResponseDescription field
            //
            $column = new TextViewColumn('ResponseDescription', 'ResponseDescription', 'Response', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('Query_Radio_ResponseGrid_ResponseDescription_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for Conclusion field
            //
            $column = new TextViewColumn('Conclusion', 'Conclusion', 'Conclusion', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('Query_Radio_ResponseGrid_Conclusion_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for Type field
            //
            $column = new TextViewColumn('Type', 'Type_Type', 'Type', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for Hauteur field
            //
            $column = new NumberViewColumn('Hauteur', 'Hauteur', 'Hauteur', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
            
            //
            // View column for Largeur field
            //
            $column = new NumberViewColumn('Largeur', 'Largeur', 'Largeur', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
            
            //
            // View column for Resolution field
            //
            $column = new NumberViewColumn('Resolution', 'Resolution', 'Resolution', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
            
            //
            // View column for Status field
            //
            $column = new TextViewColumn('Status', 'Status', 'Etat de la reponse', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for IPlusTime field
            //
            $column = new TextViewColumn('IPlusTime', 'IPlusTime', 'IPlus Time', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for Id field
            //
            $column = new TextViewColumn('Id', 'Id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('ResponseUserId', 'ResponseUserId_UserName', 'Manipulateur', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for Id field
            //
            $column = new NumberViewColumn('RequestId', 'RequestId_Id', 'Demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddCompareColumn($column);
            
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('RequestUserId', 'RequestUserId_UserName', 'Demandeur', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for NamePlusAge field
            //
            $column = new TextViewColumn('PatientId', 'PatientId_NamePlusAge', 'Patient', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for RequestDateTime field
            //
            $column = new DateTimeViewColumn('RequestDateTime', 'RequestDateTime', 'Date de demande', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestDescription', 'RequestDescription', 'Objet de la demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('Query_Radio_ResponseGrid_RequestDescription_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for ResponseImage field
            //
            $column = new ExternalImageViewColumn('ResponseImage', 'ResponseImage', 'Image', $this->dataset);
            $column->setImageHintTemplate('');
            $column->setNullLabel('');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $column->setHrefTemplate('%ResponseImage%');
            $column->setTarget('" data-fancybox="gal');
            $column->setHeight('100');
            $grid->AddCompareColumn($column);
            
            //
            // View column for ResponseDateTime field
            //
            $column = new DateTimeViewColumn('ResponseDateTime', 'ResponseDateTime', 'Date de reponse', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for ResponseDescription field
            //
            $column = new TextViewColumn('ResponseDescription', 'ResponseDescription', 'Response', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('Query_Radio_ResponseGrid_ResponseDescription_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for Conclusion field
            //
            $column = new TextViewColumn('Conclusion', 'Conclusion', 'Conclusion', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('Query_Radio_ResponseGrid_Conclusion_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for Type field
            //
            $column = new TextViewColumn('Type', 'Type_Type', 'Type', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for Hauteur field
            //
            $column = new NumberViewColumn('Hauteur', 'Hauteur', 'Hauteur', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddCompareColumn($column);
            
            //
            // View column for Largeur field
            //
            $column = new NumberViewColumn('Largeur', 'Largeur', 'Largeur', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddCompareColumn($column);
            
            //
            // View column for Resolution field
            //
            $column = new NumberViewColumn('Resolution', 'Resolution', 'Resolution', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddCompareColumn($column);
            
            //
            // View column for Status field
            //
            $column = new TextViewColumn('Status', 'Status', 'Etat de la reponse', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for IPlusTime field
            //
            $column = new TextViewColumn('IPlusTime', 'IPlusTime', 'IPlus Time', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
        }
    
        private function AddCompareHeaderColumns(Grid $grid)
        {
    
        }
    
        public function GetPageDirection()
        {
            return null;
        }
    
        public function isFilterConditionRequired()
        {
            return false;
        }
    
        protected function ApplyCommonColumnEditProperties(CustomEditColumn $column)
        {
            $column->SetDisplaySetToNullCheckBox(false);
            $column->SetDisplaySetToDefaultCheckBox(false);
    		$column->SetVariableContainer($this->GetColumnVariableContainer());
        }
    
        function CreateMasterDetailRecordGrid()
        {
            $result = new Grid($this, $this->dataset);
            
            $this->AddFieldColumns($result, false);
            $this->AddPrintColumns($result);
            
            $result->SetAllowDeleteSelected(false);
            $result->SetShowUpdateLink(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(false);
            $result->setTableBordered(false);
            $result->setTableCondensed(false);
            
            $this->setupGridColumnGroup($result);
            $this->attachGridEventHandlers($result);
            
            return $result;
        }
        
        public function OnGetFieldValue($fieldName, &$value)
        {
        if ($fieldName == 'ResponseDescription') {
            $value = nl2br(stripslashes(($value)));
            $value = preg_replace("/(<br>)+/", "<br>", $value);
        }
        
        if ($fieldName == 'Conclusion') {
            $value = nl2br(stripslashes(($value)));
            $value = preg_replace("/(<br>)+/", "<br>", $value);
        }
        
        if ($fieldName == 'RequestDescription') {
            $value = nl2br(stripslashes(($value)));
            $value = preg_replace("/(<br>)+/", "<br>", $value);
        }
        }
        
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
        protected function GetEnableModalGridDelete() { return true; }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset);
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(true);
            else
               $result->SetAllowDeleteSelected(false);   
            
            ApplyCommonPageSettings($this, $result);
            
            $result->SetUseImagesForActions(true);
            $result->SetUseFixedHeader(false);
            $result->SetShowLineNumbers(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(true);
            $result->setAllowCompare(true);
            $this->AddCompareHeaderColumns($result);
            $this->AddCompareColumns($result);
            $result->setTableBordered(false);
            $result->setTableCondensed(false);
            
            $result->SetHighlightRowAtHover(false);
            $result->SetWidth('');
            $this->AddOperationsColumns($result);
            $this->AddFieldColumns($result);
            $this->AddSingleRecordViewColumns($result);
            $this->AddEditColumns($result);
            $this->AddInsertColumns($result);
            $this->AddPrintColumns($result);
            $this->AddExportColumns($result);
    
    
            $this->SetShowPageList(true);
            $this->SetShowTopPageNavigator(true);
            $this->SetShowBottomPageNavigator(true);
            $this->setPrintListAvailable(true);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(true);
            $this->setExportListAvailable(array('excel','word','pdf'));
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array('excel','word','pdf'));
            $this->setDescription('Gestions des rapports de radiologie');
            $this->setDetailedDescription('Gestions des rapports de radiologie');
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
            $grid->SetInsertClientEditorValueChangedScript($this->RenderText('if (sender.getFieldName() == \'RequestId\')
            {
            
            var demandeID = editors[\'RequestId\'].getValue();    
            var url_demande = "radio-rapports.php?hname=insert_RequestId_Id_search&id="+demandeID;
            
            $.ajax({
                    type: "POST",
                    url: url_demande,
                    data: ({ id2: demandeID }),
                    success: function(data) {
                        ///////////////////////////////
                            editors[\'RequestUserId\'].setValue(data[0].fields.RequestUserId);
                            editors[\'PatientId\'].setValue(data[0].fields.PatientId);
                            editors[\'RequestDateTime\'].setValue(data[0].fields.RequestDateTime);
                            editors[\'RequestDescription\'].setValue(data[0].fields.RequestDescription);
                        ///////////////////////////////
                    }
                });
            }
            
            
            if (sender.getFieldName() == \'Type\')
            {
            
            var typeID = editors[\'Type\'].getValue();    
            var url_type = \'radio-rapports.php?hname=insert_Type_Type_search&id=\'+typeID;
            
            $.ajax({
                    type: "POST",
                    url: url_type,
                    data: ({ id2: typeID }),
                    success: function(data) {
            
                        ///////////////////////////////
                        editors[\'Hauteur\'].setValue(data[0].fields.Height);
                        
                        editors[\'Largeur\'].setValue(data[0].fields.Width);
                     
                        editors[\'Resolution\'].setValue(data[0].fields.Resolution);
                     
                        
                        ///////////////////////////////
                    }
                });
            
            }'));
            
            $grid->SetEditClientEditorValueChangedScript($this->RenderText('if (sender.getFieldName() == \'RequestId\')
            {
            
            var demandeID = editors[\'RequestId\'].getValue();    
            var url_demande = "radio-rapport.php?hname=insert_RequestId_Id_search&id="+demandeID;
            
            $.ajax({
                    type: "POST",
                    url: url_demande,
                    data: ({ id2: demandeID }),
                    success: function(data) {
                        ///////////////////////////////
                            editors[\'RequestUserId\'].setValue(data[0].fields.RequestUserId);
                            editors[\'PatientId\'].setValue(data[0].fields.PatientId);
                            editors[\'RequestDateTime\'].setValue(data[0].fields.RequestDateTime);
                            editors[\'RequestDescription\'].setValue(data[0].fields.RequestDescription);
                        ///////////////////////////////
                    }
                });
            }
            
            
            if (sender.getFieldName() == \'Type\')
            {
            
            var typeID = editors[\'Type\'].getValue();    
            var url_type = \'radio-rapport.php?hname=insert_Type_Type_search&id=\'+typeID;
            
            $.ajax({
                    type: "POST",
                    url: url_type,
                    data: ({ id2: typeID }),
                    success: function(data) {
            
                        ///////////////////////////////
                        editors[\'Hauteur\'].setValue(data[0].fields.Height);
                        
                        editors[\'Largeur\'].setValue(data[0].fields.Width);
                     
                        editors[\'Resolution\'].setValue(data[0].fields.Resolution);
                     
                        
                        ///////////////////////////////
                    }
                });
            
            }'));
        }
    
        protected function doRegisterHandlers() {
            $detailPage = new Query_Radio_Response_Query_Gallery_with_Response_and_RequestPage('Query_Radio_Response_Query_Gallery_with_Response_and_Request', $this, array('ResponseId'), array('Id'), $this->GetForeignKeyFields(), $this->CreateMasterDetailRecordGrid(), $this->dataset, GetCurrentUserPermissionSetForDataSource('Query Radio Response.Query Gallery with Response and Request'), 'UTF-8');
            $detailPage->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource('Query Radio Response.Query Gallery with Response and Request'));
            $detailPage->SetTitle('Album films');
            $detailPage->SetMenuLabel('Album films');
            $detailPage->SetHeader(GetPagesHeader());
            $detailPage->SetFooter(GetPagesFooter());
            $detailPage->SetHttpHandlerName('Query_Radio_Response_Query_Gallery_with_Response_and_Request_handler');
            $handler = new PageHTTPHandler('Query_Radio_Response_Query_Gallery_with_Response_and_Request_handler', $detailPage);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestDescription', 'RequestDescription', 'Objet de la demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'Query_Radio_ResponseGrid_RequestDescription_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for ResponseDescription field
            //
            $column = new TextViewColumn('ResponseDescription', 'ResponseDescription', 'Response', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'Query_Radio_ResponseGrid_ResponseDescription_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Conclusion field
            //
            $column = new TextViewColumn('Conclusion', 'Conclusion', 'Conclusion', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'Query_Radio_ResponseGrid_Conclusion_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestDescription', 'RequestDescription', 'Objet de la demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'Query_Radio_ResponseGrid_RequestDescription_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for ResponseDescription field
            //
            $column = new TextViewColumn('ResponseDescription', 'ResponseDescription', 'Response', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'Query_Radio_ResponseGrid_ResponseDescription_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Conclusion field
            //
            $column = new TextViewColumn('Conclusion', 'Conclusion', 'Conclusion', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'Query_Radio_ResponseGrid_Conclusion_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestDescription', 'RequestDescription', 'Objet de la demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'Query_Radio_ResponseGrid_RequestDescription_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for ResponseDescription field
            //
            $column = new TextViewColumn('ResponseDescription', 'ResponseDescription', 'Response', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'Query_Radio_ResponseGrid_ResponseDescription_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Conclusion field
            //
            $column = new TextViewColumn('Conclusion', 'Conclusion', 'Conclusion', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'Query_Radio_ResponseGrid_Conclusion_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);$selectQuery = 'SELECT * FROM users WHERE (Roles LIKE \'%Manipulateur%\') AND userStatus = \'0\'';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Manipulateurs');
            $field = new StringField('UserId');
            $lookupDataset->AddField($field, true);
            $field = new StringField('UserName');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserPassword');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserFullName');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Mail');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Token');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserSecretQestion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserSecretAnswer');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserStatus');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Roles');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('UserName', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_ResponseUserId_UserName_search', 'UserId', 'UserName', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);$lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`radiorequests`');
            $field = new IntegerField('Id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new IntegerField('RequestUserId');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('PatientId');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('RequestDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Status');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('UpdateID');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('UpdateIP');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('UpdateTime');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('InsertID');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('InsertIP');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('InsertTime');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('Id', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_RequestId_Id_search', 'Id', 'Id', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);$selectQuery = 'SELECT * FROM users WHERE (Roles LIKE \'%Demandeur%\') AND userStatus = \'0\'';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Demandeurs');
            $field = new StringField('UserId');
            $lookupDataset->AddField($field, true);
            $field = new StringField('UserName');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserPassword');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserFullName');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Mail');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Token');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserSecretQestion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserSecretAnswer');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserStatus');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Roles');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('UserName', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_RequestUserId_UserName_search', 'UserId', 'UserName', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);$selectQuery = 'SELECT P.*, CONCAT(P.PatientName, \' (\', P.PatientAge,\')\') AS NamePlusAge FROM patients AS P';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Patient Details');
            $field = new StringField('PatientId');
            $lookupDataset->AddField($field, true);
            $field = new StringField('PatientName');
            $lookupDataset->AddField($field, false);
            $field = new DateField('PatientBirthday');
            $lookupDataset->AddField($field, false);
            $field = new StringField('PatientAge');
            $lookupDataset->AddField($field, false);
            $field = new StringField('PatientDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('NamePlusAge');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('NamePlusAge', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_PatientId_NamePlusAge_search', 'PatientId', 'NamePlusAge', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);$lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`radiotypes`');
            $field = new IntegerField('Id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('Type');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Height');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Width');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Resolution');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('Description');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('Type', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_Type_Type_search', 'Id', 'Type', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            $selectQuery = 'SELECT * FROM users WHERE (Roles LIKE \'%Manipulateur%\') AND userStatus = \'0\'';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Manipulateurs');
            $field = new StringField('UserId');
            $lookupDataset->AddField($field, true);
            $field = new StringField('UserName');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserPassword');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserFullName');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Mail');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Token');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserSecretQestion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserSecretAnswer');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserStatus');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Roles');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('UserName', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_ResponseUserId_UserName_search', 'UserId', 'UserName', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`radiorequests`');
            $field = new IntegerField('Id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new IntegerField('RequestUserId');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('PatientId');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('RequestDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Status');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('UpdateID');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('UpdateIP');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('UpdateTime');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('InsertID');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('InsertIP');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('InsertTime');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('Id', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_RequestId_Id_search', 'Id', 'Id', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $selectQuery = 'SELECT * FROM users WHERE (Roles LIKE \'%Demandeur%\') AND userStatus = \'0\'';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Demandeurs');
            $field = new StringField('UserId');
            $lookupDataset->AddField($field, true);
            $field = new StringField('UserName');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserPassword');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserFullName');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Mail');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Token');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserSecretQestion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserSecretAnswer');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserStatus');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Roles');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('UserName', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_RequestUserId_UserName_search', 'UserId', 'UserName', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $selectQuery = 'SELECT P.*, CONCAT(P.PatientName, \' (\', P.PatientAge,\')\') AS NamePlusAge FROM patients AS P';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Patient Details');
            $field = new StringField('PatientId');
            $lookupDataset->AddField($field, true);
            $field = new StringField('PatientName');
            $lookupDataset->AddField($field, false);
            $field = new DateField('PatientBirthday');
            $lookupDataset->AddField($field, false);
            $field = new StringField('PatientAge');
            $lookupDataset->AddField($field, false);
            $field = new StringField('PatientDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('NamePlusAge');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('NamePlusAge', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_PatientId_NamePlusAge_search', 'PatientId', 'NamePlusAge', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`radiotypes`');
            $field = new IntegerField('Id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('Type');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Height');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Width');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Resolution');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('Description');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('Type', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_Type_Type_search', 'Id', 'Type', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestDescription', 'RequestDescription', 'Objet de la demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'Query_Radio_ResponseGrid_RequestDescription_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for ResponseDescription field
            //
            $column = new TextViewColumn('ResponseDescription', 'ResponseDescription', 'Response', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'Query_Radio_ResponseGrid_ResponseDescription_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Conclusion field
            //
            $column = new TextViewColumn('Conclusion', 'Conclusion', 'Conclusion', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'Query_Radio_ResponseGrid_Conclusion_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);$selectQuery = 'SELECT * FROM users WHERE (Roles LIKE \'%Manipulateur%\') AND userStatus = \'0\'';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Manipulateurs');
            $field = new StringField('UserId');
            $lookupDataset->AddField($field, true);
            $field = new StringField('UserName');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserPassword');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserFullName');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Mail');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Token');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserSecretQestion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserSecretAnswer');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserStatus');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Roles');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('UserName', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_ResponseUserId_UserName_search', 'UserId', 'UserName', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);$lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`radiorequests`');
            $field = new IntegerField('Id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new IntegerField('RequestUserId');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('PatientId');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('RequestDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Status');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('UpdateID');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('UpdateIP');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('UpdateTime');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('InsertID');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('InsertIP');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('InsertTime');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('Id', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_RequestId_Id_search', 'Id', 'Id', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);$selectQuery = 'SELECT * FROM users WHERE (Roles LIKE \'%Demandeur%\') AND userStatus = \'0\'';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Demandeurs');
            $field = new StringField('UserId');
            $lookupDataset->AddField($field, true);
            $field = new StringField('UserName');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserPassword');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserFullName');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Mail');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Token');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserSecretQestion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserSecretAnswer');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserStatus');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UserDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Roles');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('UserName', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_RequestUserId_UserName_search', 'UserId', 'UserName', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);$selectQuery = 'SELECT P.*, CONCAT(P.PatientName, \' (\', P.PatientAge,\')\') AS NamePlusAge FROM patients AS P';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Patient Details');
            $field = new StringField('PatientId');
            $lookupDataset->AddField($field, true);
            $field = new StringField('PatientName');
            $lookupDataset->AddField($field, false);
            $field = new DateField('PatientBirthday');
            $lookupDataset->AddField($field, false);
            $field = new StringField('PatientAge');
            $lookupDataset->AddField($field, false);
            $field = new StringField('PatientDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('NamePlusAge');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('NamePlusAge', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_PatientId_NamePlusAge_search', 'PatientId', 'NamePlusAge', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);$lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`radiotypes`');
            $field = new IntegerField('Id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('Type');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Height');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Width');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Resolution');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('Description');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('Type', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_Type_Type_search', 'Id', 'Type', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
        }
       
        protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderPrintColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderExportColumn($exportType, $fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomDrawRow($rowData, &$cellFontColor, &$cellFontSize, &$cellBgColor, &$cellItalicAttr, &$cellBoldAttr)
        {
    
        }
    
        protected function doExtendedCustomDrawRow($rowData, &$rowCellStyles, &$rowStyles, &$rowClasses, &$cellClasses)
        {
    
        }
    
        protected function doCustomRenderTotal($totalValue, $aggregate, $columnName, &$customText, &$handled)
        {
    
        }
    
        protected function doCustomCompareColumn($columnName, $valueA, $valueB, &$result)
        {
    
        }
    
        protected function doBeforeInsertRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
            $rowData['InsertIP'] = $_SERVER['SERVER_ADDR'];
            $rowData['InsertTime'] = SMDateTime::Now();
            $rowData['InsertID'] = $page->GetCurrentUserId(); 
            $rowData['ResponseUserId'] = $page->GetCurrentUserId();
            $rowData['ResponseDateTime'] = SMDateTime::Now();
        }
    
        protected function doBeforeUpdateRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
            $rowData['UpdateIP'] = $_SERVER['SERVER_ADDR'];
            $rowData['UpdateTime'] = SMDateTime::Now();
            $rowData['UpdateID'] = $page->GetCurrentUserId();
            $rowData['ResponseUserId'] = $page->GetCurrentUserId();
        }
    
        protected function doBeforeDeleteRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterUpdateRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
            //MAJ GLOBAL
            $ip = $_SERVER['SERVER_ADDR'];
            $now = SMDateTime::Now();
            $Id = $page->GetCurrentUserId(); 
            
            $sql =
                  "UPDATE radiorequests SET 
                  Status = '".$rowData['Status']."', 
                  UpdateID = '".$id."',
                  UpdateIP = '".$ip."', 
                  UpdateTime = '".$now."' " . 
                  "WHERE Id = '".intval($rowData['RequestId'])."'";  
                           
              $this->GetConnection()->ExecSQL($sql);
        }
    
        protected function doAfterDeleteRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doCustomHTMLHeader($page, &$customHtmlHeaderText)
        { 
    
        }
    
        protected function doGetCustomTemplate($type, $part, $mode, &$result, &$params)
        {
    
        }
    
        protected function doGetCustomExportOptions(Page $page, $exportType, $rowData, &$options)
        {
    
        }
    
        protected function doGetCustomUploadFileName($fieldName, $rowData, &$result, &$handled, $originalFileName, $originalFileExtension, $fileSize)
        {
            if ($fieldName === 'ResponseImage') {
            
                $newFileName = "Film_" . date('Y-m-d_H-i-s') ."_PT" . $rowData['PatientId'] ."_RD". $rowData['Id'] . "." . $originalFileExtension;
            
                $result = str_replace($originalFileName, $newFileName, $result);
                
                $rowData['ResponseImage'] = $newFileName;
                
                $handled = true;
            
            }
        }
    
        protected function doPrepareChart(Chart $chart)
        {
    
        }
    
        protected function doPageLoaded()
        {
    
        }
    
        protected function doGetCustomPagePermissions(Page $page, PermissionSet &$permissions, &$handled)
        {
    
        }
    
        protected function doGetCustomRecordPermissions(Page $page, &$usingCondition, $rowData, &$allowEdit, &$allowDelete, &$mergeWithDefault, &$handled)
        {
    
        }
    
    }

    SetUpUserAuthorization();

    try
    {
        $Page = new Query_Radio_ResponsePage("Query_Radio_Response", "radio-rapports.php", GetCurrentUserPermissionSetForDataSource("Query Radio Response"), 'UTF-8');
        $Page->SetTitle('Rapports de radiologie');
        $Page->SetMenuLabel('Rapports de radiologie');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("Query Radio Response"));
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
