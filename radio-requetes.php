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
    
    
    
    class radiorequestsPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`radiorequests`');
            $field = new IntegerField('Id', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new IntegerField('RequestUserId');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('PatientId');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('RequestDateTime');
            $this->dataset->AddField($field, false);
            $field = new StringField('RequestDescription');
            $this->dataset->AddField($field, false);
            $field = new StringField('Status');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('UpdateID');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('UpdateIP');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('UpdateTime');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('InsertID');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('InsertIP');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('InsertTime');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('RequestUserId', '(SELECT U.*, CONCAT(U.UserFullName, \' (\', U.UserName,\')\') AS NamePlusUsername FROM requestusers AS RU 
            INNER JOIN users AS U ON RU.UserId = U.UserId 
            WHERE U.UserStatus = \'0\')', new StringField('UserId'), new StringField('UserName', 'RequestUserId_UserName', 'RequestUserId_UserName_Query Request Details'), 'RequestUserId_UserName_Query Request Details');
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
                new FilterColumn($this->dataset, 'RequestUserId', 'RequestUserId_UserName', 'Demandeur'),
                new FilterColumn($this->dataset, 'PatientId', 'PatientId_NamePlusAge', 'Patient'),
                new FilterColumn($this->dataset, 'RequestDateTime', 'RequestDateTime', 'Request Date Time'),
                new FilterColumn($this->dataset, 'RequestDescription', 'RequestDescription', 'Demande'),
                new FilterColumn($this->dataset, 'Status', 'Status', 'Status'),
                new FilterColumn($this->dataset, 'UpdateID', 'UpdateID', 'Update ID'),
                new FilterColumn($this->dataset, 'UpdateIP', 'UpdateIP', 'Update IP'),
                new FilterColumn($this->dataset, 'UpdateTime', 'UpdateTime', 'Update Time'),
                new FilterColumn($this->dataset, 'InsertID', 'InsertID', 'Insert ID'),
                new FilterColumn($this->dataset, 'InsertIP', 'InsertIP', 'Insert IP'),
                new FilterColumn($this->dataset, 'InsertTime', 'InsertTime', 'Insert Time')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['Id'])
                ->addColumn($columns['RequestUserId'])
                ->addColumn($columns['PatientId'])
                ->addColumn($columns['RequestDateTime'])
                ->addColumn($columns['RequestDescription'])
                ->addColumn($columns['Status']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('RequestUserId')
                ->setOptionsFor('PatientId')
                ->setOptionsFor('RequestDateTime')
                ->setOptionsFor('Status');
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
            
            $main_editor = new ComboBox('status_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $main_editor->addChoice('demande', 'demande');
            $main_editor->addChoice('encours', 'encours');
            $main_editor->addChoice('reponse', 'reponse');
            $main_editor->addChoice('annule', 'annule');
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
            $column = new NumberViewColumn('Id', 'Id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
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
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for RequestDateTime field
            //
            $column = new DateTimeViewColumn('RequestDateTime', 'RequestDateTime', 'Request Date Time', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestDescription', 'RequestDescription', 'Demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('radiorequestsGrid_RequestDescription_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Status field
            //
            $column = new TextViewColumn('Status', 'Status', 'Status', $this->dataset);
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
            $column = new NumberViewColumn('Id', 'Id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
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
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for RequestDateTime field
            //
            $column = new DateTimeViewColumn('RequestDateTime', 'RequestDateTime', 'Request Date Time', $this->dataset);
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
            $column->SetFullTextWindowHandlerName('radiorequestsGrid_RequestDescription_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Status field
            //
            $column = new TextViewColumn('Status', 'Status', 'Status', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for UpdateID field
            //
            $column = new NumberViewColumn('UpdateID', 'UpdateID', 'Update ID', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for UpdateTime field
            //
            $column = new DateTimeViewColumn('UpdateTime', 'UpdateTime', 'Update Time', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for InsertID field
            //
            $column = new NumberViewColumn('InsertID', 'InsertID', 'Insert ID', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for InsertTime field
            //
            $column = new DateTimeViewColumn('InsertTime', 'InsertTime', 'Insert Time', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for RequestUserId field
            //
            $editor = new AutocompleteComboBox('requestuserid_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $selectQuery = 'SELECT U.*, CONCAT(U.UserFullName, \' (\', U.UserName,\')\') AS NamePlusUsername FROM requestusers AS RU 
            INNER JOIN users AS U ON RU.UserId = U.UserId 
            WHERE U.UserStatus = \'0\'';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Request Details');
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
            $field = new StringField('NamePlusUsername');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('UserName', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Demandeur', 'RequestUserId', 'RequestUserId_UserName', 'edit_RequestUserId_UserName_search', $editor, $this->dataset, $lookupDataset, 'UserId', 'UserName', '');
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
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for RequestDescription field
            //
            $editor = new TextAreaEdit('requestdescription_edit', 50, 8);
            $editor->setPlaceholder('Decrire la demande');
            $editColumn = new CustomEditColumn('Demande', 'RequestDescription', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Status field
            //
            $editor = new ComboBox('status_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice('demande', 'demande');
            $editor->addChoice('encours', 'encours');
            $editor->addChoice('reponse', 'reponse');
            $editor->addChoice('annule', 'annule');
            $editColumn = new CustomEditColumn('Status', 'Status', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for RequestUserId field
            //
            $editor = new AutocompleteComboBox('requestuserid_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $selectQuery = 'SELECT U.*, CONCAT(U.UserFullName, \' (\', U.UserName,\')\') AS NamePlusUsername FROM requestusers AS RU 
            INNER JOIN users AS U ON RU.UserId = U.UserId 
            WHERE U.UserStatus = \'0\'';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Request Details');
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
            $field = new StringField('NamePlusUsername');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('UserName', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Demandeur', 'RequestUserId', 'RequestUserId_UserName', 'insert_RequestUserId_UserName_search', $editor, $this->dataset, $lookupDataset, 'UserId', 'UserName', '');
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
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for RequestDescription field
            //
            $editor = new TextAreaEdit('requestdescription_edit', 50, 8);
            $editor->setPlaceholder('Decrire la demande');
            $editColumn = new CustomEditColumn('Demande', 'RequestDescription', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for Status field
            //
            $editor = new ComboBox('status_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice('demande', 'demande');
            $editor->addChoice('encours', 'encours');
            $editor->addChoice('reponse', 'reponse');
            $editor->addChoice('annule', 'annule');
            $editColumn = new CustomEditColumn('Status', 'Status', $editor, $this->dataset);
            $editColumn->SetInsertDefaultValue('demande');
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
            $column = new NumberViewColumn('Id', 'Id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
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
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for RequestDateTime field
            //
            $column = new DateTimeViewColumn('RequestDateTime', 'RequestDateTime', 'Request Date Time', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestDescription', 'RequestDescription', 'Demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('radiorequestsGrid_RequestDescription_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Status field
            //
            $column = new TextViewColumn('Status', 'Status', 'Status', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for Id field
            //
            $column = new NumberViewColumn('Id', 'Id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
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
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for RequestDateTime field
            //
            $column = new DateTimeViewColumn('RequestDateTime', 'RequestDateTime', 'Request Date Time', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestDescription', 'RequestDescription', 'Demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('radiorequestsGrid_RequestDescription_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for Status field
            //
            $column = new TextViewColumn('Status', 'Status', 'Status', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
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
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for RequestDateTime field
            //
            $column = new DateTimeViewColumn('RequestDateTime', 'RequestDateTime', 'Request Date Time', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestDescription', 'RequestDescription', 'Demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('radiorequestsGrid_RequestDescription_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for Status field
            //
            $column = new TextViewColumn('Status', 'Status', 'Status', $this->dataset);
            $column->setNullLabel('');
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
    
        public function OnGetFieldValue($fieldName, &$value)
        {
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
            $this->setDescription('Demandes de rapports de radiologies');
            $this->setDetailedDescription('Gestion des demandes de radiologies');
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            //
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestDescription', 'RequestDescription', 'Demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'radiorequestsGrid_RequestDescription_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestDescription', 'RequestDescription', 'Demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'radiorequestsGrid_RequestDescription_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestDescription', 'RequestDescription', 'Demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'radiorequestsGrid_RequestDescription_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);$selectQuery = 'SELECT U.*, CONCAT(U.UserFullName, \' (\', U.UserName,\')\') AS NamePlusUsername FROM requestusers AS RU 
            INNER JOIN users AS U ON RU.UserId = U.UserId 
            WHERE U.UserStatus = \'0\'';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Request Details');
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
            $field = new StringField('NamePlusUsername');
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
            GetApplication()->RegisterHTTPHandler($handler);
            $selectQuery = 'SELECT U.*, CONCAT(U.UserFullName, \' (\', U.UserName,\')\') AS NamePlusUsername FROM requestusers AS RU 
            INNER JOIN users AS U ON RU.UserId = U.UserId 
            WHERE U.UserStatus = \'0\'';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Request Details');
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
            $field = new StringField('NamePlusUsername');
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
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestDescription', 'RequestDescription', 'Demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'radiorequestsGrid_RequestDescription_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);$selectQuery = 'SELECT U.*, CONCAT(U.UserFullName, \' (\', U.UserName,\')\') AS NamePlusUsername FROM requestusers AS RU 
            INNER JOIN users AS U ON RU.UserId = U.UserId 
            WHERE U.UserStatus = \'0\'';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Request Details');
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
            $field = new StringField('NamePlusUsername');
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
    
        }
    
        protected function doBeforeUpdateRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doBeforeDeleteRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterUpdateRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
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
        $Page = new radiorequestsPage("radiorequests", "radio-requetes.php", GetCurrentUserPermissionSetForDataSource("radiorequests"), 'UTF-8');
        $Page->SetTitle('Demande de radiologie');
        $Page->SetMenuLabel('Demande de radiologie');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("radiorequests"));
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
