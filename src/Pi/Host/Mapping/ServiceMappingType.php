<?php

namespace Pi\Host;


enum ServiceMappingType  : string {
  Route = 'Route';
  Method = 'Method';
  EnableCors = 'EnableCors';
  Auth = 'Auth';
}
