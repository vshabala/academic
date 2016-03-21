<?php

namespace Oro\Bundle\NoteBundle\Migrations\Schema\v1_0;

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
        $table->addColumn('reporter', 'integer', ['notnull' => false]);
        $table->addColumn('assignee', 'integer', ['notnull' => false]);

        $table->addColumn('created_at', 'datetime', []);
        $table->addColumn('updated_at', 'datetime', []);
        
        $table->setPrimaryKey(['id']);
        $table->addIndex(['reporter'], 'IDX_BA066CE19EB185F9', []);
        $table->addIndex(['assignee'], 'IDX_BA066CE12793CC5E', []);

        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['reporter'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );

        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['assignee'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
    }
}
