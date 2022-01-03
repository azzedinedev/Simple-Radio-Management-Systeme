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
    
    
    
    class responseusersPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`responseusers`');
            $field = new IntegerField('responseuserId', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new IntegerField('UserId');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('UserId', '(SELECT U.*, CONCAT(U.UserFullName, \' (\', U.UserName,\')\') AS NamePlusUsername FROM users AS U 
            WHERE U.UserStatus = \'0\')', new StringField('UserId'), new StringField('NamePlusUsername', 'UserId_NamePlusUsername', 'UserId_NamePlusUsername_Query Users details'), 'UserId_NamePlusUsername_Query Users details');
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
                new FilterColumn($this->dataset, 'responseuserId', 'responseuserId', 'Id'),
                new FilterColumn($this->dataset, 'UserId', 'UserId_NamePlusUsername', 'Manipulateur')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['responseuserId'])
                ->addColumn($columns['UserId']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('UserId');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('responseuserid_edit');
            
            $filterBuilder->addColumn(
                $columns['responseuserId'],
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
            
            $main_editor = new AutocompleteComboBox('userid_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_UserId_NamePlusUsername_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('UserId', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_UserId_NamePlusUsername_search');
            
            $filterBuilder->addColumn(
                $columns['UserId'],
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
            // View column for responseuserId field
            //
            $column = new StringTransformViewColumn('responseuserId', 'responseuserId', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setStringTransformFunction('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for NamePlusUsername field
            //
            $column = new TextViewColumn('UserId', 'UserId_NamePlusUsername', 'Manipulateur', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for responseuserId field
            //
            $column = new StringTransformViewColumn('responseuserId', 'responseuserId', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setStringTransformFunction('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for NamePlusUsername field
            //
            $column = new TextViewColumn('UserId', 'UserId_NamePlusUsername', 'Manipulateur', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for UserId field
            //
            $editor = new AutocompleteComboBox('userid_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $selectQuery = 'SELECT U.*, CONCAT(U.UserFullName, \' (\', U.UserName,\')\') AS NamePlusUsername FROM users AS U 
            WHERE U.UserStatus = \'0\'';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Users details');
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
            $lookupDataset->setOrderByField('NamePlusUsername', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Manipulateur', 'UserId', 'UserId_NamePlusUsername', 'edit_UserId_NamePlusUsername_search', $editor, $this->dataset, $lookupDataset, 'UserId', 'NamePlusUsername', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for UserId field
            //
            $editor = new AutocompleteComboBox('userid_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $selectQuery = 'SELECT U.*, CONCAT(U.UserFullName, \' (\', U.UserName,\')\') AS NamePlusUsername FROM users AS U 
            WHERE U.UserStatus = \'0\'';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Users details');
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
            $lookupDataset->setOrderByField('NamePlusUsername', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Manipulateur', 'UserId', 'UserId_NamePlusUsername', 'insert_UserId_NamePlusUsername_search', $editor, $this->dataset, $lookupDataset, 'UserId', 'NamePlusUsername', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for responseuserId field
            //
            $column = new StringTransformViewColumn('responseuserId', 'responseuserId', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setStringTransformFunction('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for NamePlusUsername field
            //
            $column = new TextViewColumn('UserId', 'UserId_NamePlusUsername', 'Manipulateur', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for responseuserId field
            //
            $column = new StringTransformViewColumn('responseuserId', 'responseuserId', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setStringTransformFunction('');
            $grid->AddExportColumn($column);
            
            //
            // View column for NamePlusUsername field
            //
            $column = new TextViewColumn('UserId', 'UserId_NamePlusUsername', 'Manipulateur', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for NamePlusUsername field
            //
            $column = new TextViewColumn('UserId', 'UserId_NamePlusUsername', 'Manipulateur', $this->dataset);
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
            $this->setDescription('Manipulateurs de radiologie');
            $this->setDetailedDescription('Gestion de la liste des réalisateurs d\'examen de radiologie sur les patients (Généralement des manipulateurs radio)');
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            $selectQuery = 'SELECT U.*, CONCAT(U.UserFullName, \' (\', U.UserName,\')\') AS NamePlusUsername FROM users AS U 
            WHERE U.UserStatus = \'0\'';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Users details');
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
            $lookupDataset->setOrderByField('NamePlusUsername', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_UserId_NamePlusUsername_search', 'UserId', 'NamePlusUsername', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            $selectQuery = 'SELECT U.*, CONCAT(U.UserFullName, \' (\', U.UserName,\')\') AS NamePlusUsername FROM users AS U 
            WHERE U.UserStatus = \'0\'';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Users details');
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
            $lookupDataset->setOrderByField('NamePlusUsername', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_UserId_NamePlusUsername_search', 'UserId', 'NamePlusUsername', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);$selectQuery = 'SELECT U.*, CONCAT(U.UserFullName, \' (\', U.UserName,\')\') AS NamePlusUsername FROM users AS U 
            WHERE U.UserStatus = \'0\'';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Users details');
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
            $lookupDataset->setOrderByField('NamePlusUsername', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_UserId_NamePlusUsername_search', 'UserId', 'NamePlusUsername', null, 20);
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
        $Page = new responseusersPage("responseusers", "manipulateurs2.php", GetCurrentUserPermissionSetForDataSource("responseusers"), 'UTF-8');
        $Page->SetTitle('Manipulateurs');
        $Page->SetMenuLabel('Manipulateurs');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("responseusers"));
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
