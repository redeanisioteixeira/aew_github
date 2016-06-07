<?php
/**
 * Sec Date
 *
 * @author diego
 *
 */
class Sec_Date extends Zend_Date
{
    const DB_DATETIME = 'YYYY-MM-dd HH:mm:ss';
    const DB_DATE = 'YYYY-MM-dd';
    const DATE = 'dd/MM/YYYY';
    const DATETIME = 'dd/MM/YYYY HH:mm';

    /**
     * Devolve a data sempre no padrao do banco
     * @param Zend_Date|string $value
     * @return string
     */
    public static function setDoctrineDate($value)
    {
       if(is_null($value) || empty($value))
            return null;

        if($value instanceof Zend_Date) {
            return $value->toString(Sec_Date::DB_DATETIME);
        } else {
            $value = new Sec_Date($value);
            return $value->toString(Sec_Date::DB_DATETIME);
        }
    }

    /**
     * Devolve a data sempre no padrao do banco
     * @param Zend_Date|string $value
     * @return string
     */
    public static function getDoctrineDate($value)
    {
        return self::setDoctrineDate($value);
    }


    /**
     * Devolve a data para apresentacao
     * @param Zend_Date|string $value
     * @param $format
     * @return string
     */
    public static function getPresentationDate($value, $format = Sec_Date::DATE)
    {
       if(is_null($value) || empty($value))
            return null;

        if($value instanceof Zend_Date) {
            return $value->toString($format);
        } else {
            $value = new Sec_Date($value);
            return $value->toString($format);
        }
    }
}