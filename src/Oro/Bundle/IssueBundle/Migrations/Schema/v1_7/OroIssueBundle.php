<?php

namespace Oro\Bundle\IssueBundle\Migrations\Schema\v1_7;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;



class OroIssueBundle implements Migration
{

    /**
     * @var string
     */
    protected $relatedTableName = 'oro_issue_related';

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $table = $schema->createTable($this->relatedTableName);

        $table->addColumn('issue_source', 'integer', []);
        $table->addColumn('issue_target', 'integer', []);

        $table->setPrimaryKey(['issue_source','issue_target']);

        $table->addIndex(['issue_source'], 'UNIQ_DB84AA212EA230E1', []);
        $table->addIndex(['issue_target'], 'UNIQ_DB8BC21211750E1', []);

        $table->addForeignKeyConstraint(
            $schema->getTable('oro_issue'),
            ['issue_source'],
            ['id'],
            ['onDelete' => 'CASCADE']
        );

        $table->addForeignKeyConstraint(
            $schema->getTable('oro_issue'),
            ['issue_target'],
            ['id'],
            ['onDelete' => 'CASCADE']
        );

    }


}