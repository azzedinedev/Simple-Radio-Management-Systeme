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
    
    
    
    class users02Page extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`users`');
            $field = new IntegerField('UserId', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('UserName');
            $this->dataset->AddField($field, false);
            $field = new StringField('UserPassword');
            $this->dataset->AddField($field, false);
            $field = new StringField('UserFullName');
            $this->dataset->AddField($field, false);
            $field = new StringField('Mail');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('Token');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('UserSecretQestion');
            $this->dataset->AddField($field, false);
            $field = new StringField('UserSecretAnswer');
            $this->dataset->AddField($field, false);
            $field = new StringField('UserStatus');
            $this->dataset->AddField($field, false);
            $field = new StringField('UserDescription');
            $this->dataset->AddField($field, false);
            $field = new StringField('Roles');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $this->dataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), '(UserId = %CURRENT_USER_ID%)'));
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function CreatePageNavigator()
        {
            return null;
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
                new FilterColumn($this->dataset, 'UserId', 'UserId', 'Id'),
                new FilterColumn($this->dataset, 'UserName', 'UserName', 'Pseudo'),
                new FilterColumn($this->dataset, 'UserPassword', 'UserPassword', 'Mot de passe'),
                new FilterColumn($this->dataset, 'UserFullName', 'UserFullName', 'Nom'),
                new FilterColumn($this->dataset, 'Mail', 'Mail', 'Mail'),
                new FilterColumn($this->dataset, 'Token', 'Token', 'Token'),
                new FilterColumn($this->dataset, 'UserSecretQestion', 'UserSecretQestion', 'User Secret Qestion'),
                new FilterColumn($this->dataset, 'UserSecretAnswer', 'UserSecretAnswer', 'User Secret Answer'),
                new FilterColumn($this->dataset, 'UserStatus', 'UserStatus', 'Etat'),
                new FilterColumn($this->dataset, 'UserDescription', 'UserDescription', 'Description'),
                new FilterColumn($this->dataset, 'Roles', 'Roles', 'Roles')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
    
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
    
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
    
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
                $operation = new AjaxOperation(OPERATION_EDIT,
                    $this->GetLocalizerCaptions()->GetMessageString('Edit'),
                    $this->GetLocalizerCaptions()->GetMessageString('Edit'), $this->dataset,
                    $this->GetGridEditHandler(), $grid, AjaxOperation::INLINE);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowEditButtonHandler', $this);
            }
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('UserName', 'UserName', 'Pseudo', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_UserName_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for UserFullName field
            //
            $column = new TextViewColumn('UserFullName', 'UserFullName', 'Nom', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_UserFullName_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Mail field
            //
            $column = new TextViewColumn('Mail', 'Mail', 'Mail', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_Mail_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for UserDescription field
            //
            $column = new TextViewColumn('UserDescription', 'UserDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_UserDescription_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Roles field
            //
            $column = new TextViewColumn('Roles', 'Roles', 'Roles', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_Roles_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for UserId field
            //
            $column = new NumberViewColumn('UserId', 'UserId', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('UserName', 'UserName', 'Pseudo', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_UserName_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for UserFullName field
            //
            $column = new TextViewColumn('UserFullName', 'UserFullName', 'Nom', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_UserFullName_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Mail field
            //
            $column = new TextViewColumn('Mail', 'Mail', 'Mail', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_Mail_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Token field
            //
            $column = new TextViewColumn('Token', 'Token', 'Token', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_Token_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for UserStatus field
            //
            $column = new TextViewColumn('UserStatus', 'UserStatus', 'Etat', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for UserDescription field
            //
            $column = new TextViewColumn('UserDescription', 'UserDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_UserDescription_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Roles field
            //
            $column = new TextViewColumn('Roles', 'Roles', 'Roles', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_Roles_handler_view');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for UserFullName field
            //
            $editor = new TextEdit('userfullname_edit');
            $editColumn = new CustomEditColumn('Nom', 'UserFullName', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Mail field
            //
            $editor = new TextEdit('mail_edit');
            $editColumn = new CustomEditColumn('Mail', 'Mail', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for UserDescription field
            //
            $editor = new TextAreaEdit('userdescription_edit', 50, 8);
            $editColumn = new CustomEditColumn('Description', 'UserDescription', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
    
            $grid->SetShowAddButton(false && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for UserId field
            //
            $column = new NumberViewColumn('UserId', 'UserId', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('UserName', 'UserName', 'Pseudo', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_UserName_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for UserPassword field
            //
            $column = new TextViewColumn('UserPassword', 'UserPassword', 'Mot de passe', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_UserPassword_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for UserFullName field
            //
            $column = new TextViewColumn('UserFullName', 'UserFullName', 'Nom', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_UserFullName_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Mail field
            //
            $column = new TextViewColumn('Mail', 'Mail', 'Mail', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_Mail_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Token field
            //
            $column = new TextViewColumn('Token', 'Token', 'Token', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_Token_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for UserSecretQestion field
            //
            $column = new TextViewColumn('UserSecretQestion', 'UserSecretQestion', 'User Secret Qestion', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_UserSecretQestion_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for UserSecretAnswer field
            //
            $column = new TextViewColumn('UserSecretAnswer', 'UserSecretAnswer', 'User Secret Answer', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_UserSecretAnswer_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for UserStatus field
            //
            $column = new TextViewColumn('UserStatus', 'UserStatus', 'Etat', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for UserDescription field
            //
            $column = new TextViewColumn('UserDescription', 'UserDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_UserDescription_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Roles field
            //
            $column = new TextViewColumn('Roles', 'Roles', 'Roles', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_Roles_handler_print');
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for UserId field
            //
            $column = new NumberViewColumn('UserId', 'UserId', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('UserName', 'UserName', 'Pseudo', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_UserName_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for UserPassword field
            //
            $column = new TextViewColumn('UserPassword', 'UserPassword', 'Mot de passe', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_UserPassword_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for UserFullName field
            //
            $column = new TextViewColumn('UserFullName', 'UserFullName', 'Nom', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_UserFullName_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for Mail field
            //
            $column = new TextViewColumn('Mail', 'Mail', 'Mail', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_Mail_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for Token field
            //
            $column = new TextViewColumn('Token', 'Token', 'Token', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_Token_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for UserSecretQestion field
            //
            $column = new TextViewColumn('UserSecretQestion', 'UserSecretQestion', 'User Secret Qestion', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_UserSecretQestion_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for UserSecretAnswer field
            //
            $column = new TextViewColumn('UserSecretAnswer', 'UserSecretAnswer', 'User Secret Answer', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_UserSecretAnswer_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for UserStatus field
            //
            $column = new TextViewColumn('UserStatus', 'UserStatus', 'Etat', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for UserDescription field
            //
            $column = new TextViewColumn('UserDescription', 'UserDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_UserDescription_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for Roles field
            //
            $column = new TextViewColumn('Roles', 'Roles', 'Roles', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('users02Grid_Roles_handler_export');
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
    
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
        if ($fieldName == 'UserDescription') {
            $value = nl2br(stripslashes(($value)));
            $value = preg_replace("/(<br>)+/", "<br>", $value);
        }
        
        if ($fieldName == 'UserFullName') {
            $value = nl2br(stripslashes(($value)));
            $value = preg_replace("/(<br>)+/", "<br>", $value);
        }
        
        if ($fieldName == 'UserSecretQestion') {
            $value = nl2br(stripslashes(($value)));
            $value = preg_replace("/(<br>)+/", "<br>", $value);
        }
         
        if ($fieldName == 'UserSecretAnswer') {
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
        public function GetEnableModalGridEdit() { return true; }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset);
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(false);
            else
               $result->SetAllowDeleteSelected(false);   
            
            ApplyCommonPageSettings($this, $result);
            
            $result->SetUseImagesForActions(true);
            $result->SetUseFixedHeader(false);
            $result->SetShowLineNumbers(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetViewMode(ViewMode::CARD);
            $result->SetCardCountInRow(array(
                'lg' => 1,
                'md' => 1,
                'sm' => 1,
                'xs' => 1
            ));
            $result->setEnableRuntimeCustomization(false);
            $result->SetShowUpdateLink(false);
            $result->setAllowAddMultipleRecords(false);
            $result->setTableBordered(false);
            $result->setTableCondensed(false);
            
            $result->SetHighlightRowAtHover(true);
            $result->SetWidth('');
            $this->AddOperationsColumns($result);
            $this->AddFieldColumns($result);
            $this->AddSingleRecordViewColumns($result);
            $this->AddEditColumns($result);
            $this->AddInsertColumns($result);
            $this->AddPrintColumns($result);
            $this->AddExportColumns($result);
    
    
            $this->SetViewFormTitle('Profile : %UserName%');
            $this->SetEditFormTitle('Profile : %UserName%');
            $this->SetShowPageList(true);
            $this->SetShowTopPageNavigator(false);
            $this->SetShowBottomPageNavigator(false);
            $this->setPrintListAvailable(true);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(true);
            $this->setExportListAvailable(array('excel','word','xml','csv','pdf'));
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array('excel','word','xml','csv','pdf'));
            $this->setDescription('Affichage et modification du profile utilisateur en cours');
            $this->setDetailedDescription('Affichage et modification du profile utilisateur en cours');
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
            $grid->SetEditClientFormLoadedScript($this->RenderText('editors[\'UserPassword\'].setValue(\'\');'));
        }
    
        protected function doRegisterHandlers() {
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('UserName', 'UserName', 'Pseudo', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'users02Grid_UserName_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for UserFullName field
            //
            $column = new TextViewColumn('UserFullName', 'UserFullName', 'Nom', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'users02Grid_UserFullName_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Mail field
            //
            $column = new TextViewColumn('Mail', 'Mail', 'Mail', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'users02Grid_Mail_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for UserDescription field
            //
            $column = new TextViewColumn('UserDescription', 'UserDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'users02Grid_UserDescription_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Roles field
            //
            $column = new TextViewColumn('Roles', 'Roles', 'Roles', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'users02Grid_Roles_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for UserName field
            //
            $column = new TextViewColumn('UserName', 'UserName', 'Pseudo', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'users02Grid_UserName_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for UserPassword field
            //
            $column = new TextViewColumn('UserPassword', 'UserPassword', 'Mot de passe', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'users02Grid_UserPassword_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for UserFullName field
            //
            $column = new TextViewColumn('UserFullName', 'UserFullName', 'Nom', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'users02Grid_UserFullName_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Mail field
            //
            $column = new TextViewColumn('Mail', 'Mail', 'Mail', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'users02Grid_Mail_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Token field
            //
            $column = new TextViewColumn('Token', 'Token', 'Token', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'users02Grid_Token_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for UserSecretQestion field
            //
            $column = new TextViewColumn('UserSecretQestion', 'UserSecretQestion', 'User Secret Qestion', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'users02Grid_UserSecretQestion_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for UserSecretAnswer field
            //
            $column = new TextViewColumn('UserSecretAnswer', 'UserSecretAnswer', 'User Secret Answer', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'users02Grid_UserSecretAnswer_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for UserDescription field
            //
            $column = new TextViewColumn('UserDescription', 'UserDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'users02Grid_UserDescription_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Roles field
            //
            $column = new TextViewColumn('Roles', 'Roles', 'Roles', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'users02Grid_Roles_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('UserName', 'UserName', 'Pseudo', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'users02Grid_UserName_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for UserFullName field
            //
            $column = new TextViewColumn('UserFullName', 'UserFullName', 'Nom', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'users02Grid_UserFullName_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Mail field
            //
            $column = new TextViewColumn('Mail', 'Mail', 'Mail', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'users02Grid_Mail_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Token field
            //
            $column = new TextViewColumn('Token', 'Token', 'Token', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'users02Grid_Token_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for UserDescription field
            //
            $column = new TextViewColumn('UserDescription', 'UserDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'users02Grid_UserDescription_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Roles field
            //
            $column = new TextViewColumn('Roles', 'Roles', 'Roles', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'users02Grid_Roles_handler_view', $column);
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
            $rowData['UserPassword'] = md5($rowData['UserPassword']);
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
        $Page = new users02Page("users02", "mon-profile.php", GetCurrentUserPermissionSetForDataSource("users02"), 'UTF-8');
        $Page->SetTitle('Mon profile');
        $Page->SetMenuLabel('Mon profile');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("users02"));
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
