<?php

/**
 * Trait implementing the hydratation logic to a class
 * @author Gwenaël
 * @version 1
 */
trait Hydrator {
        /**
         * Hydrate the object
         * @param array $datas The data array to hydrate the class
         */
        protected function hydrate(array $datas) : void {
                foreach($datas as $key => $value) {
                        $methodName = "set".ucwords($key);
                        if(method_exists($this, $methodName)) {
                                $this->$methodName($value);
                        }
                }
        }
}
