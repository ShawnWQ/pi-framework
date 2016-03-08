<?hh

namespace Pi\ServiceInterface;

use Pi\Service;


class WhoisService extends Service {

}

enum RecordType : string {
  Ns = 'DNS_Ns';
  Mx = 'DNS_Mx';
  A = 'DNS_A';
}

class DnsRecord {

  protected $host;

  protected $type;

  protected $priority;

  protected $target;

  protected $class;

  protected $ttl;

  public function host()
  {
    return $this->host;
  }

  public function type()
  {
    return $this->type;
  }

  public function priority()
  {
    return $this->priority;
  }

  public function target()
  {
    return $this->target;
  }

  public function class()
  {
    return $this->class;
  }

  public function ttl()
  {
    return $this->ttl;
  }
}
class WhoisBusiness {


  public function getValue(string $hostname, RecordType $type)
  {

  }

  public function getValues(string $hostname, ?array $excludes = null)
  {
    if(!is_null($excludes)) {
      $records = dns_get_record('php.net', DNS_ALL - $excludes);
    } else {
      $records = dns_get_record("php.net", DNS_ANY, $authns, $addtl);
    }

    return $this->formatRecords($records);
  }

  protected function formatRecords(array $records)
  {
    return array_map(function($record){
      $record = new DnsRecord();
    }, $records);

  }
}
