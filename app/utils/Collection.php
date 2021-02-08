<?php

namespace App\utils;


class Collection implements \IteratorAggregate, \ArrayAccess {
    public $items;

    /**
     * Collection constructor.
     * @param array $items
     */
    public function __construct(array $items){
        $this->items = $items;
    }

    /**
     * Affiche la valeur de la clé $key
     *
     * @param $key
     * @return mixed
     */
    public function get($key) {
        $index = explode('.', $key);
        return $this->getValue($index, $this->items);
    }

    /**
     * injecte une valeur '$value' à la clé '$key' mis en paramètre
     * @param $key
     * @param $value
     */
    public function set($key, $value) {
        $this->items[$key] = $value;
    }

    /**
     * Récupère les données de 2 colonnes d'un tableau
     * @param $key - données de la 1ere colonne
     * @param $value - données de la 2eme colonne
     * @return Collection
     */
    public function lists($key, $value) {
        $results = [];
        foreach($this->items as $item){
            $results[$item[$key]] = $item[$value];
        }
        return new Collection($results);
    }

    /**
     * Extrait les valeurs d'une clé $key spécifique
     * @param $key
     * @return Collection
     */
    public function extract($key) {
        $results = [];
        foreach($this->items as $item){
            $results[] = $item[$key];
        }
        return new Collection($results);
    }

    /**
     * Création d'une liste séparé par un caractère spécifique
     * Semblable à la fonction implode.
     *
     * @param $glue - représente le caractère
     * @return string
     */
    public function join($glue) {
        return implode($glue, $this->items);
    }

    /**
     * Retourne la valeur maximale d'une clé donné en paramètre
     *
     * @param bool $key
     * @return mixed
     */
    public function max($key = false) {
        if($key) {
            return $this->extract($key)->max();
        } else {
            return max($this->items);
        }
    }

    /**
     * Retourne la valeur minimale d'une clé donné en paramètre
     *
     * @param bool $key
     * @return mixed
     */
    public function min($key = false) {
        if($key) {
            return $this->extract($key)->min();
        } else {
            return min($this->items);
        }
    }

    /**
     * Add an element in the current array $this->items
     * @param $key
     * @param $value
     * @return Collection
     */
    public function insert($key, $value)
    {
        return new Collection(array_splice($this->items, $key, 0, [$value]));
    }

    /**
     * Sorts a collection of arrays or objects by key.
     * @param string $attr
     * @param string $order
     * @return Collection
     */
    public function orderBy(string $attr, string $order)
    {
        $sortedItems = [];
        foreach ($this->items as $item) {
            $key = is_object($item) ? $item->{$attr} : $item[$attr];
            $sortedItems[$key] = $item;
        }
        if ($order === 'desc') {
            krsort($sortedItems);
        } else {
            ksort($sortedItems);
        }

        return new Collection(array_values($sortedItems));
    }

    /**
     * Returns an array with n elements removed from the beginning.
     * @param int $n
     * @return array
     */
    public function take($n = 1)
    {
        return array_slice($this->items,0, $n);
    }

    /**
     * Retourne si la clé existe.
     * @param $key
     * @return bool
     */
    private function has($key) {
        return array_key_exists($key, $this->items);
    }

    /**
     * Récupère les valeurs '$value' d'une suite de clé '$indexes'

     * @param array $indexes
     * @param $value
     * @return null|Collection
     */
    private function getValue(array $indexes, $value) {
        $key = array_shift($indexes);
        if(empty($indexes)) {
            if(!array_key_exists($key, $value)){
                return null;
            }
            if(is_array($value[$key])) {
                return new Collection($value[$key]);
            } else {
                return $value[$key];
            }
        } else {
            return $this->getValue($indexes, $value[$key]);
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        return $this->set($offset, $value);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        if($this->has($offset)){
            unset($this->items[$offset]);
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }
}