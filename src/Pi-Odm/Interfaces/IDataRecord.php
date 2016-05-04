<?hh

namespace Pi\Odm\Interfaces;

/**
 * Provides access to the document properties values within each document for a DataReader
 */
interface IDataRecord {

  /**
   * Count the properties in the current document
   */
  public function getFieldCount();
  public function item(string $key);

  /**
   * Gets the value located at the specified index.
   */
  public function itemByIndex($index);

  /**
   * Gets the value of the specified property as a Boolean.
   */
  public function getBoolean(string $key);

  /**
   * Gets the 8-bit unsigned integer value of the specified property
   */
  public function getByte(string $key);

  /**
   * Reads a stream of bytes from the specified property offset into the buffer as an array, starting at the given buffer offset.
   * @param string $key          The index within the field from which to start the read operation.
   * @param [type] $fieldOffset  The index within the field from which to start the read operation.
   * @param [type] $buffer       The buffer into which to read the stream of bytes.
   * @param [type] $bufferOffset The index for buffer to start the read operation.
   * @param [type] $length       The number of bytes to read.
   */
  public function getBytes(string $key, $fieldOffset, $buffer, $bufferOffset, $length);

  /**
   * [getData description]
   * @param string $key Returns an IDataReader for the specified column ordinal.
   * @return IDataReader
   */
  public function getData(string $key);

  /**
   * Gets the date and time data value of the specified field.
   * @param string $key [description]
   */
  public function getDateTime(string $key);

  /**
   * Gets the fixed-position numeric value of the specified field.
   */
  public function getDecimal(string $key);

  /**
   * Gets the double-precision floating point number of the specified field.
   * @param string $key [description]
   */
  public function getDouble(string $key);

  /**
   * Gets the string value of the specified field.
   * @param string $key [description]
   * @return string
   */
  public function getString(string $key) : string;

  /**
   * Return the value of the specified field.
   * @param strng $key [description]
   */
  public function getValue(strng $key);

  /**
   * Populates an array of objects with the column values of the current record.
   * @param string $key [description]
   */
  public function getValues(string $key);

  /**
   * Return whether the specified field is set to null.
   * @param string $key [description]
   */
  public function isDbNull(string $key);
}
