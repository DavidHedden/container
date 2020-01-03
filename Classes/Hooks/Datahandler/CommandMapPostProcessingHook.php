<?php

namespace  B13\Container\Hooks\Datahandler;


use B13\Container\Domain\Factory\Exception;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use B13\Container\Domain\Factory\ContainerFactory;

class CommandMapPostProcessingHook
{
    /**
     * @var ContainerFactory
     */
    protected $containerFactory = null;

    /**
     * @param ContainerFactory|null $containerFactory
     */
    public function __construct(ContainerFactory $containerFactory = null)
    {
        $this->containerFactory = $containerFactory ?? GeneralUtility::makeInstance(ContainerFactory::class);
    }

    /**
     * @param string $command
     * @param string $table
     * @param int $id
     * @param mixed $value
     * @param DataHandler $dataHandler
     * @param $pasteUpdate
     * @param $pasteDatamap
     * @return void
     */
    public function processCmdmap_postProcess(string $command, string $table, int $id, $value, DataHandler $dataHandler, $pasteUpdate, $pasteDatamap): void
    {
        $action = (string)$value['action'];
        if ($table === 'tt_content' && $command === 'version' && $action === 'new' && !empty($dataHandler->copyMappingArray['tt_content'][$id])) {
            $newId = (int)$dataHandler->copyMappingArray['tt_content'][$id];
            $this->verionizeChilds($id, $newId, $dataHandler);
        }
        if ($table === 'tt_content' && $command === 'copy' && !empty($pasteDatamap['tt_content'])) {
            $this->copyOrMoveChilds($id, $value, (int)array_key_first($pasteDatamap['tt_content']),'copy', $dataHandler);
        } elseif ($table === 'tt_content' && $command === 'move') {
            $this->copyOrMoveChilds($id, $value, $id,'move', $dataHandler);
        } elseif ($table === 'tt_content' && $command === 'localize') {
            $this->localizeOrCopyToLanguage($id, $value, 'localize', $dataHandler);
        }
    }

    /**
     * @param int $origContainerUid
     * @param int $newContainerUid
     * @param DataHandler $dataHandler
     * @return void
     */
    protected function verionizeChilds(int $origContainerUid, int $newContainerUid, DataHandler $dataHandler): void
    {
        try {
            $container = $this->containerFactory->buildContainer($origContainerUid);
            $childs = $container->getChildRecords();
            $datamap = ['tt_content' => []];
            foreach ($childs as $colPos => $record) {
                $datamap['tt_content'][$record['uid']] = [
                    'tx_container_parent' => $newContainerUid,
                ];
            }
            if (count($datamap['tt_content']) > 0) {
                $localDataHandler = GeneralUtility::makeInstance(DataHandler::class);
                $localDataHandler->start($datamap, [], $dataHandler->BE_USER);
                $localDataHandler->process_datamap();
            }
        } catch (Exception $e) {
            // nothing todo
        }
    }

    /**
     * @param int $uid
     * @param int $language
     * @param string $command
     * @param DataHandler $dataHandler
     * @return void
     */
    protected function localizeOrCopyToLanguage(int $uid, int $language, string $command, DataHandler $dataHandler): void
    {
        try {
            $container = $this->containerFactory->buildContainer($uid);
            $childs = $container->getChildRecords();
            $cmd = ['tt_content' => []];
            foreach ($childs as $colPos => $record) {
                $cmd['tt_content'][$record['uid']] = [$command => $language];
            }
            if (count($cmd['tt_content']) > 0) {
                $localDataHandler = GeneralUtility::makeInstance(DataHandler::class);
                $localDataHandler->start([], $cmd, $dataHandler->BE_USER);
                $localDataHandler->process_cmdmap();
            }
        } catch (Exception $e) {
            // nothing todo
        }
    }

    /**
     * @param int $origUid
     * @param int $newId
     * @param int $containerId
     * @param string $command
     * @param DataHandler $dataHandler
     * @return void
     */
    protected function copyOrMoveChilds(int $origUid, int $newId, int $containerId, string $command, DataHandler $dataHandler): void
    {
        try {
            // when moving or copy a container into other language the other language is returned
            $container = $this->containerFactory->buildContainer($origUid);
            $childs = $container->getChildRecords();
            $cmd = ['tt_content' => []];
            foreach ($childs as $colPos => $record) {
                $cmd['tt_content'][$record['uid']] = [
                    $command => [
                        'action' => 'paste',
                        'target' => $newId,
                        'update' => [
                            'tx_container_parent' => $containerId,
                            'colPos' =>  $record['colPos']
                        ]
                    ]
                ];
            }
            if (count($cmd['tt_content']) > 0) {
                $localDataHandler = GeneralUtility::makeInstance(DataHandler::class);
                $localDataHandler->start([], $cmd, $dataHandler->BE_USER);
                $localDataHandler->process_cmdmap();
            }
        } catch (Exception $e) {
            // nothing todo
        }
    }
}
