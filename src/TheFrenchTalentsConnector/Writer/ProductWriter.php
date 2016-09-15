<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\Writer;

use Akeneo\Component\Batch\Model\StepExecution;
use Akeneo\Component\Batch\Item\AbstractConfigurableStepElement;
use Akeneo\Component\Batch\Item\ItemWriterInterface;
use Akeneo\Component\Batch\Step\StepExecutionAwareInterface;
use Kiboko\Component\Connector\ConfigurationAwareTrait;
use Kiboko\Component\Connector\NameAwareTrait;
use Pim\Bundle\BaseConnectorBundle\Writer\File\CsvWriter;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ProductWriter
    extends AbstractConfigurableStepElement
    implements ItemWriterInterface, StepExecutionAwareInterface
{
    use ConfigurationAwareTrait;
    use NameAwareTrait;

    private $writer;

    public function __construct(CsvWriter $writer)
    {
        $this->writer = $writer;
    }

    /**
     * @return array
     */
    public function getConfigurationFields()
    {
        return [
            'productFilePath' => [
                'options' => [
                    'label' => 'luni_tft_connector.steps.reader.productFilePath.label',
                    'help'  => 'luni_tft_connector.steps.reader.productFilePath.help'
                ],
            ],
        ];
    }

    /**
     * Get the step element configuration (based on its properties)
     *
     * @return array
     */
    public function getConfiguration()
    {
        return [
            'productFilePath' => $this->writer->getFilePath()
        ];
    }

    /**
     * Set the step element configuration
     *
     * @param array $config
     */
    public function setConfiguration(array $config)
    {
        $this->writer->setConfiguration([
            'filePath'      => isset($config['productFilePath']) ? $config['productFilePath'] : null,
            'delimiter'     => ';',
            'enclosure'     => '"',
            'escape'        => '"',
            'withHeader'    => true,
        ]);
    }

    public function getProductFilePath()
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        return $accessor->getValue($this->writer, 'filePath');
    }

    public function setProductFilePath($filePath)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $accessor->setValue($this->writer, 'filePath', $filePath);
    }

    /**
     * {@inheritdoc}
     */
    public function write(array $items)
    {
        $this->writer->write($items);
    }

    /**
     * {@inheritdoc}
     */
    public function initialize()
    {
        parent::initialize();

        $this->writer->initialize();
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        parent::flush();

        $this->writer->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function setStepExecution(StepExecution $stepExecution)
    {
        $this->writer->setStepExecution($stepExecution);
    }
}
