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
    
    
    
    class Query_Manipulateurs_radioresponsePage extends DetailPage
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`radioresponse`');
            $field = new IntegerField('Id', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new IntegerField('ResponseUserId');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('RequestId');
            $field->SetIsNotNull(true);
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
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('Hauteur');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('Largeur');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('Resolution');
            $field->SetIsNotNull(true);
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
            $this->dataset->AddLookupField('RequestId', 'radiorequests', new IntegerField('Id', null, null, true), new StringField('RequestDescription', 'RequestId_RequestDescription', 'RequestId_RequestDescription_radiorequests'), 'RequestId_RequestDescription_radiorequests');
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
                new FilterColumn($this->dataset, 'ResponseUserId', 'ResponseUserId', 'Response User Id'),
                new FilterColumn($this->dataset, 'RequestId', 'RequestId_RequestDescription', 'Demande'),
                new FilterColumn($this->dataset, 'ResponseImage', 'ResponseImage', 'Image'),
                new FilterColumn($this->dataset, 'ResponseDateTime', 'ResponseDateTime', 'Date de la reponse'),
                new FilterColumn($this->dataset, 'ResponseDescription', 'ResponseDescription', 'Reponse'),
                new FilterColumn($this->dataset, 'Conclusion', 'Conclusion', 'Conclusion'),
                new FilterColumn($this->dataset, 'Type', 'Type_Type', 'Type'),
                new FilterColumn($this->dataset, 'Hauteur', 'Hauteur', 'Hauteur'),
                new FilterColumn($this->dataset, 'Largeur', 'Largeur', 'Largeur'),
                new FilterColumn($this->dataset, 'Resolution', 'Resolution', 'Resolution'),
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
                ->addColumn($columns['RequestId'])
                ->addColumn($columns['ResponseImage'])
                ->addColumn($columns['ResponseDateTime'])
                ->addColumn($columns['ResponseDescription'])
                ->addColumn($columns['Conclusion'])
                ->addColumn($columns['Type'])
                ->addColumn($columns['Hauteur'])
                ->addColumn($columns['Largeur'])
                ->addColumn($columns['Resolution']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('RequestId')
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
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new AutocompleteComboBox('requestid_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_RequestId_RequestDescription_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('RequestId', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_RequestId_RequestDescription_search');
            
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
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('responseimage_edit');
            
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
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestId', 'RequestId_RequestDescription', 'Demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ResponseImage field
            //
            $column = new ExternalImageViewColumn('ResponseImage', 'ResponseImage', 'Image', $this->dataset);
            $column->setImageHintTemplate('');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $column->setHeight('100');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ResponseDateTime field
            //
            $column = new DateTimeViewColumn('ResponseDateTime', 'ResponseDateTime', 'Date de la reponse', $this->dataset);
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
            $column = new TextViewColumn('ResponseDescription', 'ResponseDescription', 'Reponse', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridQuery Manipulateurs.radioresponse_ResponseDescription_handler_list');
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
            $column->SetFullTextWindowHandlerName('DetailGridQuery Manipulateurs.radioresponse_Conclusion_handler_list');
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
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridQuery Manipulateurs.radioresponse_Type_Type_handler_list');
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
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestId', 'RequestId_RequestDescription', 'Demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ResponseImage field
            //
            $column = new ExternalImageViewColumn('ResponseImage', 'ResponseImage', 'Image', $this->dataset);
            $column->setImageHintTemplate('');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $column->setHeight('100');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ResponseDateTime field
            //
            $column = new DateTimeViewColumn('ResponseDateTime', 'ResponseDateTime', 'Date de la reponse', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ResponseDescription field
            //
            $column = new TextViewColumn('ResponseDescription', 'ResponseDescription', 'Reponse', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridQuery Manipulateurs.radioresponse_ResponseDescription_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Conclusion field
            //
            $column = new TextViewColumn('Conclusion', 'Conclusion', 'Conclusion', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridQuery Manipulateurs.radioresponse_Conclusion_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Type field
            //
            $column = new TextViewColumn('Type', 'Type_Type', 'Type', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridQuery Manipulateurs.radioresponse_Type_Type_handler_view');
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
        }
    
        protected function AddEditColumns(Grid $grid)
        {
    
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
    
            $grid->SetShowAddButton(false && $this->GetSecurityInfo()->HasAddGrant());
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
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestId', 'RequestId_RequestDescription', 'Demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for ResponseImage field
            //
            $column = new ExternalImageViewColumn('ResponseImage', 'ResponseImage', 'Image', $this->dataset);
            $column->setImageHintTemplate('');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $column->setHeight('100');
            $grid->AddPrintColumn($column);
            
            //
            // View column for ResponseDateTime field
            //
            $column = new DateTimeViewColumn('ResponseDateTime', 'ResponseDateTime', 'Date de la reponse', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for ResponseDescription field
            //
            $column = new TextViewColumn('ResponseDescription', 'ResponseDescription', 'Reponse', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridQuery Manipulateurs.radioresponse_ResponseDescription_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Conclusion field
            //
            $column = new TextViewColumn('Conclusion', 'Conclusion', 'Conclusion', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridQuery Manipulateurs.radioresponse_Conclusion_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Type field
            //
            $column = new TextViewColumn('Type', 'Type_Type', 'Type', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridQuery Manipulateurs.radioresponse_Type_Type_handler_print');
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
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestId', 'RequestId_RequestDescription', 'Demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for ResponseImage field
            //
            $column = new ExternalImageViewColumn('ResponseImage', 'ResponseImage', 'Image', $this->dataset);
            $column->setImageHintTemplate('');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $column->setHeight('100');
            $grid->AddExportColumn($column);
            
            //
            // View column for ResponseDateTime field
            //
            $column = new DateTimeViewColumn('ResponseDateTime', 'ResponseDateTime', 'Date de la reponse', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for ResponseDescription field
            //
            $column = new TextViewColumn('ResponseDescription', 'ResponseDescription', 'Reponse', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridQuery Manipulateurs.radioresponse_ResponseDescription_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for Conclusion field
            //
            $column = new TextViewColumn('Conclusion', 'Conclusion', 'Conclusion', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridQuery Manipulateurs.radioresponse_Conclusion_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for Type field
            //
            $column = new TextViewColumn('Type', 'Type_Type', 'Type', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridQuery Manipulateurs.radioresponse_Type_Type_handler_export');
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
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestId', 'RequestId_RequestDescription', 'Demande', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for ResponseImage field
            //
            $column = new ExternalImageViewColumn('ResponseImage', 'ResponseImage', 'Image', $this->dataset);
            $column->setImageHintTemplate('');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $column->setHeight('100');
            $grid->AddCompareColumn($column);
            
            //
            // View column for ResponseDateTime field
            //
            $column = new DateTimeViewColumn('ResponseDateTime', 'ResponseDateTime', 'Date de la reponse', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for ResponseDescription field
            //
            $column = new TextViewColumn('ResponseDescription', 'ResponseDescription', 'Reponse', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridQuery Manipulateurs.radioresponse_ResponseDescription_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for Conclusion field
            //
            $column = new TextViewColumn('Conclusion', 'Conclusion', 'Conclusion', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridQuery Manipulateurs.radioresponse_Conclusion_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for Type field
            //
            $column = new TextViewColumn('Type', 'Type_Type', 'Type', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridQuery Manipulateurs.radioresponse_Type_Type_handler_compare');
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
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            //
            // View column for ResponseDescription field
            //
            $column = new TextViewColumn('ResponseDescription', 'ResponseDescription', 'Reponse', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridQuery Manipulateurs.radioresponse_ResponseDescription_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Conclusion field
            //
            $column = new TextViewColumn('Conclusion', 'Conclusion', 'Conclusion', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridQuery Manipulateurs.radioresponse_Conclusion_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Type field
            //
            $column = new TextViewColumn('Type', 'Type_Type', 'Type', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridQuery Manipulateurs.radioresponse_Type_Type_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for ResponseDescription field
            //
            $column = new TextViewColumn('ResponseDescription', 'ResponseDescription', 'Reponse', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridQuery Manipulateurs.radioresponse_ResponseDescription_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Conclusion field
            //
            $column = new TextViewColumn('Conclusion', 'Conclusion', 'Conclusion', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridQuery Manipulateurs.radioresponse_Conclusion_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Type field
            //
            $column = new TextViewColumn('Type', 'Type_Type', 'Type', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridQuery Manipulateurs.radioresponse_Type_Type_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for ResponseDescription field
            //
            $column = new TextViewColumn('ResponseDescription', 'ResponseDescription', 'Reponse', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridQuery Manipulateurs.radioresponse_ResponseDescription_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Conclusion field
            //
            $column = new TextViewColumn('Conclusion', 'Conclusion', 'Conclusion', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridQuery Manipulateurs.radioresponse_Conclusion_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Type field
            //
            $column = new TextViewColumn('Type', 'Type_Type', 'Type', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridQuery Manipulateurs.radioresponse_Type_Type_handler_compare', $column);
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
            $lookupDataset->setOrderByField('RequestDescription', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_RequestId_RequestDescription_search', 'Id', 'RequestDescription', null, 20);
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
            // View column for ResponseDescription field
            //
            $column = new TextViewColumn('ResponseDescription', 'ResponseDescription', 'Reponse', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridQuery Manipulateurs.radioresponse_ResponseDescription_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Conclusion field
            //
            $column = new TextViewColumn('Conclusion', 'Conclusion', 'Conclusion', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridQuery Manipulateurs.radioresponse_Conclusion_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Type field
            //
            $column = new TextViewColumn('Type', 'Type_Type', 'Type', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridQuery Manipulateurs.radioresponse_Type_Type_handler_view', $column);
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
    
    // OnBeforePageExecute event handler
    
    
    
    class Query_ManipulateursPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $selectQuery = 'SELECT * FROM users WHERE (Roles LIKE \'%Manipulateur%\') AND userStatus = \'0\'';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $this->dataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Manipulateurs');
            $field = new StringField('UserId');
            $this->dataset->AddField($field, true);
            $field = new StringField('UserName');
            $this->dataset->AddField($field, false);
            $field = new StringField('UserPassword');
            $this->dataset->AddField($field, false);
            $field = new StringField('UserFullName');
            $this->dataset->AddField($field, false);
            $field = new StringField('Mail');
            $this->dataset->AddField($field, false);
            $field = new StringField('Token');
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
                new FilterColumn($this->dataset, 'UserId', 'UserId', 'Id'),
                new FilterColumn($this->dataset, 'UserName', 'UserName', 'Pseudo'),
                new FilterColumn($this->dataset, 'UserPassword', 'UserPassword', 'User Password'),
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
            $quickFilter
                ->addColumn($columns['UserId'])
                ->addColumn($columns['UserName'])
                ->addColumn($columns['UserFullName'])
                ->addColumn($columns['Mail'])
                ->addColumn($columns['UserDescription'])
                ->addColumn($columns['Roles']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
    
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('userid_edit');
            
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
            
            $main_editor = new TextEdit('username_edit');
            
            $filterBuilder->addColumn(
                $columns['UserName'],
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
            
            $main_editor = new TextEdit('UserFullName');
            
            $filterBuilder->addColumn(
                $columns['UserFullName'],
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
            
            $main_editor = new TextEdit('mail_edit');
            
            $filterBuilder->addColumn(
                $columns['Mail'],
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
            
            $main_editor = new TextEdit('UserDescription');
            
            $filterBuilder->addColumn(
                $columns['UserDescription'],
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
            
            $main_editor = new TextEdit('roles_edit');
            
            $filterBuilder->addColumn(
                $columns['Roles'],
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
    
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            if (GetCurrentUserPermissionSetForDataSource('Query Manipulateurs.radioresponse')->HasViewGrant() && $withDetails)
            {
            //
            // View column for Query_Manipulateurs_radioresponse detail
            //
            $column = new DetailColumn(array('UserId'), 'Query Manipulateurs.radioresponse', 'Query_Manipulateurs_radioresponse_handler', $this->dataset, 'Manipultations');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            }
            
            //
            // View column for UserId field
            //
            $column = new TextViewColumn('UserId', 'UserId', 'Id', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('UserName', 'UserName', 'Pseudo', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
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
            $column->SetFullTextWindowHandlerName('Query_ManipulateursGrid_UserFullName_handler_list');
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
            $column->SetFullTextWindowHandlerName('Query_ManipulateursGrid_UserDescription_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Roles field
            //
            $column = new TextViewColumn('Roles', 'Roles', 'Roles', $this->dataset);
            $column->SetOrderable(true);
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
            $column = new TextViewColumn('UserId', 'UserId', 'Id', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('UserName', 'UserName', 'Pseudo', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for UserFullName field
            //
            $column = new TextViewColumn('UserFullName', 'UserFullName', 'Nom', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('Query_ManipulateursGrid_UserFullName_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Mail field
            //
            $column = new TextViewColumn('Mail', 'Mail', 'Mail', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for UserDescription field
            //
            $column = new TextViewColumn('UserDescription', 'UserDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('Query_ManipulateursGrid_UserDescription_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Roles field
            //
            $column = new TextViewColumn('Roles', 'Roles', 'Roles', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
    
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
            $column = new TextViewColumn('UserId', 'UserId', 'Id', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('UserName', 'UserName', 'Pseudo', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for UserFullName field
            //
            $column = new TextViewColumn('UserFullName', 'UserFullName', 'Nom', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('Query_ManipulateursGrid_UserFullName_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Mail field
            //
            $column = new TextViewColumn('Mail', 'Mail', 'Mail', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for UserDescription field
            //
            $column = new TextViewColumn('UserDescription', 'UserDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('Query_ManipulateursGrid_UserDescription_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Roles field
            //
            $column = new TextViewColumn('Roles', 'Roles', 'Roles', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for UserId field
            //
            $column = new TextViewColumn('UserId', 'UserId', 'Id', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('UserName', 'UserName', 'Pseudo', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for UserFullName field
            //
            $column = new TextViewColumn('UserFullName', 'UserFullName', 'Nom', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('Query_ManipulateursGrid_UserFullName_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for Mail field
            //
            $column = new TextViewColumn('Mail', 'Mail', 'Mail', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for UserDescription field
            //
            $column = new TextViewColumn('UserDescription', 'UserDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('Query_ManipulateursGrid_UserDescription_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for Roles field
            //
            $column = new TextViewColumn('Roles', 'Roles', 'Roles', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for UserId field
            //
            $column = new TextViewColumn('UserId', 'UserId', 'Id', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('UserName', 'UserName', 'Pseudo', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for UserFullName field
            //
            $column = new TextViewColumn('UserFullName', 'UserFullName', 'Nom', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('Query_ManipulateursGrid_UserFullName_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for Mail field
            //
            $column = new TextViewColumn('Mail', 'Mail', 'Mail', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for UserDescription field
            //
            $column = new TextViewColumn('UserDescription', 'UserDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('Query_ManipulateursGrid_UserDescription_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for Roles field
            //
            $column = new TextViewColumn('Roles', 'Roles', 'Roles', $this->dataset);
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
        
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
    
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
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            $detailPage = new Query_Manipulateurs_radioresponsePage('Query_Manipulateurs_radioresponse', $this, array('ResponseUserId'), array('UserId'), $this->GetForeignKeyFields(), $this->CreateMasterDetailRecordGrid(), $this->dataset, GetCurrentUserPermissionSetForDataSource('Query Manipulateurs.radioresponse'), 'UTF-8');
            $detailPage->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource('Query Manipulateurs.radioresponse'));
            $detailPage->SetTitle('Manipultations');
            $detailPage->SetMenuLabel('Manipultations');
            $detailPage->SetHeader(GetPagesHeader());
            $detailPage->SetFooter(GetPagesFooter());
            $detailPage->SetHttpHandlerName('Query_Manipulateurs_radioresponse_handler');
            $handler = new PageHTTPHandler('Query_Manipulateurs_radioresponse_handler', $detailPage);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for UserFullName field
            //
            $column = new TextViewColumn('UserFullName', 'UserFullName', 'Nom', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'Query_ManipulateursGrid_UserFullName_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for UserDescription field
            //
            $column = new TextViewColumn('UserDescription', 'UserDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'Query_ManipulateursGrid_UserDescription_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for UserFullName field
            //
            $column = new TextViewColumn('UserFullName', 'UserFullName', 'Nom', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'Query_ManipulateursGrid_UserFullName_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for UserDescription field
            //
            $column = new TextViewColumn('UserDescription', 'UserDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'Query_ManipulateursGrid_UserDescription_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for UserFullName field
            //
            $column = new TextViewColumn('UserFullName', 'UserFullName', 'Nom', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'Query_ManipulateursGrid_UserFullName_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for UserDescription field
            //
            $column = new TextViewColumn('UserDescription', 'UserDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'Query_ManipulateursGrid_UserDescription_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for UserFullName field
            //
            $column = new TextViewColumn('UserFullName', 'UserFullName', 'Nom', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'Query_ManipulateursGrid_UserFullName_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for UserDescription field
            //
            $column = new TextViewColumn('UserDescription', 'UserDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'Query_ManipulateursGrid_UserDescription_handler_view', $column);
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
        $Page = new Query_ManipulateursPage("Query_Manipulateurs", "manipulateurs.php", GetCurrentUserPermissionSetForDataSource("Query Manipulateurs"), 'UTF-8');
        $Page->SetTitle('Manipulateurs');
        $Page->SetMenuLabel('Manipulateurs');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("Query Manipulateurs"));
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
