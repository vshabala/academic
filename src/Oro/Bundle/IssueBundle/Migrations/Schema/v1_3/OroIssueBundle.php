<?php

namespace Oro\Bundle\IssueBundle\Migrations\Schema\v1_3;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;



class OroIssueBundle implements Migration
{
    /**
     * @var string
     */
    protected $issuePriorityTableName = 'oro_issue_priority';
    /**
     * @var string
     */
    protected $issueResolutionTableName = 'oro_issue_resolution';
    /**
     * @var string
     */
    protected $issueTableName = 'oro_issue';



    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $this->createIssuePriorityTable($schema);
        $this->createIssueResolutionTable($schema);
        $this->modifyIssueTable($schema);
    }

    /**
     * @param Schema $schema
     */
    protected function createIssuePriorityTable(Schema $schema)
    {
        if ($schema->hasTable($this->issuePriorityTableName)) {
            $schema->dropTable($this->issuePriorityTableName);
        }
        $table = $schema->createTable($this->issuePriorityTableName);
        $table->addColumn('name', 'string', ['notnull' => true, 'length' => 32]);
        $table->addColumn('label', 'string', ['notnull' => true, 'length' => 255]);
        $table->addColumn('`order`', 'integer', ['notnull' => true]);
        $table->setPrimaryKey(['name']);
        $table->addUniqueIndex(['label'], 'UNIQ_DB8472D3EA750E1');
    }

    /**
     * @param Schema $schema
     */
    protected function createIssueResolutionTable(Schema $schema)
    {
        if ($schema->hasTable($this->issueResolutionTableName)) {
            $schema->dropTable($this->issueResolutionTableName);
        }
        $table = $schema->createTable($this->issueResolutionTableName);
        $table->addColumn('name', 'string', ['notnull' => true, 'length' => 32]);
        $table->addColumn('label', 'string', ['notnull' => true, 'length' => 255]);
        $table->addColumn('`order`', 'integer', ['notnull' => true]);
        $table->setPrimaryKey(['name']);
        $table->addUniqueIndex(['label'], 'UNIQ_DB8472D3EA750E2');
    }

    /**
     * @param Schema $schema
     */
    protected function modifyIssueTable(Schema $schema)
    {
        $table = $schema->getTable($this->issueTableName);

        $table->addColumn('issue_priority_name', 'string', ['notnull' => false, 'length' => 32]);
        $table->addColumn('issue_resolution_name', 'string', ['notnull' => false, 'length' => 32]);

        $table->addIndex(['issue_priority_name'], 'IDX_814DEE3FD34C1C4E', []);
        $table->addIndex(['issue_resolution_name'], 'IDX_8141233FD34C1C4E', []);

        $table->addForeignKeyConstraint(
            $schema->getTable($this->issuePriorityTableName),
            ['issue_priority_name'],
            ['name'],
            ['onDelete' => 'SET NULL']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable($this->issueResolutionTableName),
            ['issue_resolution_name'],
            ['name'],
            ['onDelete' => 'SET NULL']
        );

    }

}