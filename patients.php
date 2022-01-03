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
    
    
    
    class patientsPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`patients`');
            $field = new IntegerField('PatientId', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('PatientName');
            $this->dataset->AddField($field, false);
            $field = new DateField('PatientBirthday');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('PatientAge');
            $this->dataset->AddField($field, false);
            $field = new StringField('PatientDescription');
            $this->dataset->AddField($field, false);
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
                new FilterColumn($this->dataset, 'PatientId', 'PatientId', 'Id'),
                new FilterColumn($this->dataset, 'PatientName', 'PatientName', 'Nom du patient'),
                new FilterColumn($this->dataset, 'PatientBirthday', 'PatientBirthday', 'Date de naissance'),
                new FilterColumn($this->dataset, 'PatientAge', 'PatientAge', 'Age'),
                new FilterColumn($this->dataset, 'PatientDescription', 'PatientDescription', 'Description')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['PatientId'])
                ->addColumn($columns['PatientName'])
                ->addColumn($columns['PatientBirthday'])
                ->addColumn($columns['PatientAge'])
                ->addColumn($columns['PatientDescription']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('PatientBirthday');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('patientid_edit');
            
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
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('patientname_edit');
            $main_editor->SetPlaceholder('Ecrire le nom complet du patient');
            
            $filterBuilder->addColumn(
                $columns['PatientName'],
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
            
            $main_editor = new DateTimeEdit('patientbirthday_edit', false, 'Y-m-d');
            
            $filterBuilder->addColumn(
                $columns['PatientBirthday'],
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
            
            $main_editor = new SpinEdit('patientage_edit');
            
            $filterBuilder->addColumn(
                $columns['PatientAge'],
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
            
            $main_editor = new TextEdit('PatientDescription');
            
            $filterBuilder->addColumn(
                $columns['PatientDescription'],
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
            //
            // View column for PatientId field
            //
            $column = new NumberViewColumn('PatientId', 'PatientId', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for PatientName field
            //
            $column = new TextViewColumn('PatientName', 'PatientName', 'Nom du patient', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('patientsGrid_PatientName_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for PatientBirthday field
            //
            $column = new DateTimeViewColumn('PatientBirthday', 'PatientBirthday', 'Date de naissance', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for PatientAge field
            //
            $column = new NumberViewColumn('PatientAge', 'PatientAge', 'Age', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetEscapeHTMLSpecialChars(true);
            $column->SetWordWrap(false);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for PatientDescription field
            //
            $column = new TextViewColumn('PatientDescription', 'PatientDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('patientsGrid_PatientDescription_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for PatientId field
            //
            $column = new NumberViewColumn('PatientId', 'PatientId', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for PatientName field
            //
            $column = new TextViewColumn('PatientName', 'PatientName', 'Nom du patient', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('patientsGrid_PatientName_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for PatientBirthday field
            //
            $column = new DateTimeViewColumn('PatientBirthday', 'PatientBirthday', 'Date de naissance', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for PatientAge field
            //
            $column = new NumberViewColumn('PatientAge', 'PatientAge', 'Age', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetEscapeHTMLSpecialChars(true);
            $column->SetWordWrap(false);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for PatientDescription field
            //
            $column = new TextViewColumn('PatientDescription', 'PatientDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('patientsGrid_PatientDescription_handler_view');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for PatientName field
            //
            $editor = new TextEdit('patientname_edit');
            $editor->SetPlaceholder('Ecrire le nom complet du patient');
            $editColumn = new CustomEditColumn('Nom du patient', 'PatientName', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for PatientBirthday field
            //
            $editor = new DateTimeEdit('patientbirthday_edit', false, 'Y-m-d');
            $editColumn = new CustomEditColumn('Date de naissance', 'PatientBirthday', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for PatientAge field
            //
            $editor = new SpinEdit('patientage_edit');
            $editColumn = new CustomEditColumn('Age', 'PatientAge', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for PatientDescription field
            //
            $editor = new TextAreaEdit('patientdescription_edit', 50, 8);
            $editor->setPlaceholder('Décrire le patient');
            $editColumn = new CustomEditColumn('Description', 'PatientDescription', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for PatientName field
            //
            $editor = new TextEdit('patientname_edit');
            $editor->SetPlaceholder('Ecrire le nom complet du patient');
            $editColumn = new CustomEditColumn('Nom du patient', 'PatientName', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for PatientBirthday field
            //
            $editor = new DateTimeEdit('patientbirthday_edit', false, 'Y-m-d');
            $editColumn = new CustomEditColumn('Date de naissance', 'PatientBirthday', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for PatientAge field
            //
            $editor = new SpinEdit('patientage_edit');
            $editColumn = new CustomEditColumn('Age', 'PatientAge', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for PatientDescription field
            //
            $editor = new TextAreaEdit('patientdescription_edit', 50, 8);
            $editor->setPlaceholder('Décrire le patient');
            $editColumn = new CustomEditColumn('Description', 'PatientDescription', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for PatientId field
            //
            $column = new NumberViewColumn('PatientId', 'PatientId', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for PatientName field
            //
            $column = new TextViewColumn('PatientName', 'PatientName', 'Nom du patient', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('patientsGrid_PatientName_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for PatientBirthday field
            //
            $column = new DateTimeViewColumn('PatientBirthday', 'PatientBirthday', 'Date de naissance', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for PatientAge field
            //
            $column = new NumberViewColumn('PatientAge', 'PatientAge', 'Age', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetEscapeHTMLSpecialChars(true);
            $column->SetWordWrap(false);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for PatientDescription field
            //
            $column = new TextViewColumn('PatientDescription', 'PatientDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('patientsGrid_PatientDescription_handler_print');
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for PatientId field
            //
            $column = new NumberViewColumn('PatientId', 'PatientId', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for PatientName field
            //
            $column = new TextViewColumn('PatientName', 'PatientName', 'Nom du patient', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('patientsGrid_PatientName_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for PatientBirthday field
            //
            $column = new DateTimeViewColumn('PatientBirthday', 'PatientBirthday', 'Date de naissance', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for PatientAge field
            //
            $column = new NumberViewColumn('PatientAge', 'PatientAge', 'Age', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetEscapeHTMLSpecialChars(true);
            $column->SetWordWrap(false);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for PatientDescription field
            //
            $column = new TextViewColumn('PatientDescription', 'PatientDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('patientsGrid_PatientDescription_handler_export');
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for PatientName field
            //
            $column = new TextViewColumn('PatientName', 'PatientName', 'Nom du patient', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('patientsGrid_PatientName_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for PatientBirthday field
            //
            $column = new DateTimeViewColumn('PatientBirthday', 'PatientBirthday', 'Date de naissance', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for PatientAge field
            //
            $column = new NumberViewColumn('PatientAge', 'PatientAge', 'Age', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetEscapeHTMLSpecialChars(true);
            $column->SetWordWrap(false);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for PatientDescription field
            //
            $column = new TextViewColumn('PatientDescription', 'PatientDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('patientsGrid_PatientDescription_handler_compare');
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
            $this->setDescription('Gestion de la liste des patients');
            $this->setDetailedDescription('Gestion de la liste des patients');
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
            $grid->SetInsertClientEditorValueChangedScript($this->RenderText('//Initialiser l\'age
            var age = 0;
            //Verifier la date de naissance
              if (editors[\'PatientBirthday\'].getValue() != "" ) { 
            
                var today = new Date();
                var dtn   = sender.getValue(); // on lit la date de naissance
                var an    = dtn.substr(0,4);   // l\'année (les quatre premiers caractères de la chaîne à partir de 6)
                var mois  = dtn.substr(5,2);   // On selectionne le mois de la date de naissance
                var day   = dtn.substr(8,2);   // On selectionne la jour de la date de naissance
            
                var dateNaissance = new Date(an + "-" + mois + "-" + day);
            
                age = today.getFullYear() - dateNaissance.getFullYear();
                var m = today.getMonth() - dateNaissance.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < dateNaissance.getDate())) {
                    age = age - 1;
                }
                 editors[\'PatientAge\'].setValue(age); // que l\'on place dans le input d\'id Age
              }else{
                 age = editors[\'PatientAge\'].getValue();
              }
            
            //Verifier l\'age
            if( (age < 0) || (age > 120) ){
                 alert("Il semble que l\'age n\'est pas normal. Veuillez le revoir");
                 editors[\'PatientBirthday\'].setValue(\'\');
                 editors[\'PatientAge\'].setValue(\'\');
            }'));
            
            $grid->SetEditClientEditorValueChangedScript($this->RenderText('//Initialiser l\'age
            var age = 0;
            //Verifier la date de naissance
              if (editors[\'PatientBirthday\'].getValue() != "" ) { 
            
                var today = new Date();
                var dtn   = sender.getValue(); // on lit la date de naissance
                var an    = dtn.substr(0,4);   // l\'année (les quatre premiers caractères de la chaîne à partir de 6)
                var mois  = dtn.substr(5,2);   // On selectionne le mois de la date de naissance
                var day   = dtn.substr(8,2);   // On selectionne la jour de la date de naissance
            
                var dateNaissance = new Date(an + "-" + mois + "-" + day);
            
                age = today.getFullYear() - dateNaissance.getFullYear();
                var m = today.getMonth() - dateNaissance.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < dateNaissance.getDate())) {
                    age = age - 1;
                }
                 editors[\'PatientAge\'].setValue(age); // que l\'on place dans le input d\'id Age
              }else{
                 age = editors[\'PatientAge\'].getValue();
              }
            
            //Verifier l\'age
            if( (age < 0) || (age > 120) ){
                 alert("Il semble que l\'age n\'est pas normal. Veuillez le revoir");
                 editors[\'PatientBirthday\'].setValue(\'\');
                 editors[\'PatientAge\'].setValue(\'\');
            }'));
        }
    
        protected function doRegisterHandlers() {
            //
            // View column for PatientName field
            //
            $column = new TextViewColumn('PatientName', 'PatientName', 'Nom du patient', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'patientsGrid_PatientName_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for PatientDescription field
            //
            $column = new TextViewColumn('PatientDescription', 'PatientDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'patientsGrid_PatientDescription_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for PatientName field
            //
            $column = new TextViewColumn('PatientName', 'PatientName', 'Nom du patient', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'patientsGrid_PatientName_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for PatientDescription field
            //
            $column = new TextViewColumn('PatientDescription', 'PatientDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'patientsGrid_PatientDescription_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for PatientName field
            //
            $column = new TextViewColumn('PatientName', 'PatientName', 'Nom du patient', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'patientsGrid_PatientName_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for PatientDescription field
            //
            $column = new TextViewColumn('PatientDescription', 'PatientDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'patientsGrid_PatientDescription_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for PatientName field
            //
            $column = new TextViewColumn('PatientName', 'PatientName', 'Nom du patient', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'patientsGrid_PatientName_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for PatientDescription field
            //
            $column = new TextViewColumn('PatientDescription', 'PatientDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'patientsGrid_PatientDescription_handler_view', $column);
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
        $Page = new patientsPage("patients", "patients.php", GetCurrentUserPermissionSetForDataSource("patients"), 'UTF-8');
        $Page->SetTitle('Patients');
        $Page->SetMenuLabel('Patients');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("patients"));
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
