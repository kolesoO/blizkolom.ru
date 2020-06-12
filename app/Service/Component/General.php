<?php

namespace App\Service\Component;

class General
{
    public static $serviceName = "component";

    private $viewResult;

    private $paramsVariableName = "arParams";

    private $resultVariableName = "arResult";

    private $tmpDir = "component";

    private $compName;

    /**
     * @param string $compName
     * @param string $compTmp
     * @param array $arParams
     * @return mixed
     */
    public function includeComponent(string $compName, string $compTmp, array $arParams)
    {
        $className = __NAMESPACE__ . "\\" . ucfirst($compName);
        $rsComp = new $className;
        if ($rsComp instanceof Base) {
            $rsComp->setParams($rsComp->prepareParams($arParams));
            $rsComp->execute();
            $this->compName = $compName;
            $this->includeTemplate($rsComp, $compTmp);
            return $this->viewResult;
        }

        return null;
    }

    /**
     * @param Base $rsComp
     * @param string $tmpName
     */
    private function includeTemplate(Base $rsComp, string $tmpName): void
    {
        $tmpName = $this->tmpDir . "." . $this->compName . "." . $tmpName;
        if (\view()->exists($tmpName)) {
            $this->viewResult = \view($tmpName, [
                $this->paramsVariableName => $rsComp->getParams(),
                $this->resultVariableName => $rsComp->getResult()
            ]);
        } else {
            $this->viewResult = $rsComp->getResult();
        }
    }
}
