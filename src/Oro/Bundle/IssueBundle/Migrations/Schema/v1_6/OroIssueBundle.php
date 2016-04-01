<?php

namespace Oro\Bundle\IssueBundle\Migrations\Schema\v1_6;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;



class OroIssueBundle implements Migration
{

    /**
     * @var string
     */
    protected $collaboratorTableName = 'oro_issue_collaborator';

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $table = $schema->createTable($this->collaboratorTableName);

        $table->addColumn('issue_id', 'integer');
        $table->addColumn('user_id', 'integer');
        $table->setPrimaryKey(['issue_id','user_id']);

        $table->addIndex(['issue_id'], 'UNIQ_DB847212EA230E1', []);
        $table->addIndex(['user_id'], 'UNIQ_DB84721211750E1', []);

        $table->addForeignKeyConstraint(
            $schema->getTable('oro_issue'),
            ['issue_id'],
            ['id'],
            ['onDelete' => 'CASCADE']
        );

        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['user_id'],
            ['id'],
            ['onDelete' => 'CASCADE']
        );

    }
    

}