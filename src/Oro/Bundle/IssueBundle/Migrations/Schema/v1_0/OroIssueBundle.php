<?php

namespace Oro\Bundle\IssueBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class OroIssueBundle implements Migration
{
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        self::addIssueTable($schema);
    }

    /**
     * {@inheritdoc}
     */
    public static function addIssueTable(Schema $schema)
    {
        $table = $schema->createTable('oro_issue');

        $table->addColumn('id', 'integer', ['autoincrement' => true]);

        $table->addColumn('code', 'string', ['length' => 50]);
        $table->addColumn('summary', 'text');
        $table->addColumn('description', 'text');
        $table->addColumn('user_reporter_id', 'integer', ['notnull' => false]);
        $table->addColumn('user_assignee_id', 'integer', ['notnull' => false]);
        $table->addColumn('organization_id', 'integer', ['notnull' => false]);
        $table->addColumn('workflow_item_id', 'integer', ['notnull' => false]);
        $table->addColumn('workflow_step_id', 'integer', ['notnull' => false]);

        $table->addColumn('created_at', 'datetime', []);
        $table->addColumn('updated_at', 'datetime', []);
        
        $table->setPrimaryKey(['id']);
        $table->addIndex(['user_reporter_id'], 'IDX_BA066CE19EB185F9', []);
        $table->addIndex(['user_assignee_id'], 'IDX_BA066CE12793CC5E', []);
        $table->addIndex(['workflow_item_id'], 'IDX_814DEE3F71FE882C', []);
        $table->addIndex(['workflow_step_id'], 'IDX_814DEE3F71FE882F', []);


        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['user_reporter_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );

        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['user_assignee_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );

        $table->addForeignKeyConstraint(
            $schema->getTable('oro_workflow_step'),
            ['workflow_step_id'],
            ['id'],
            ['onDelete' => 'SET NULL']
        );

        $table->addForeignKeyConstraint(
            $schema->getTable('oro_workflow_item'),
            ['workflow_item_id'],
            ['id'],
            ['onDelete' => 'SET NULL']
        );
    }
}
