<?php

namespace Oro\Bundle\IssueBundle\Migrations\Schema\v1_5;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;



class OroIssueBundle implements Migration
{

    /**
     * @var string
     */
    protected $issueTableName = 'oro_issue';

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $table = $schema->getTable($this->issueTableName);
        $table->addColumn('parent_id', 'integer', ["notnull"=>false]);

        $table->addIndex(['parent_id'], 'UNIQ_DB847212EA750E1', []);

        $table->addForeignKeyConstraint(
               $schema->getTable($this->issueTableName),
               ['parent_id'],
               ['id'],
               ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );


    }



}