<?php
  use App\Helpers\SecurityHelper;
  $previous = [];
  $ongoing  = [];
  $upcoming = [];
  date_default_timezone_set('Asia/Kolkata');
  $time = date("YmdHi", time());
  foreach($programList as $flight) {
    $fEnd   = isset($flight->end)   ? (string)$flight->end   : '';
    $fStart = isset($flight->start) ? (string)$flight->start : '';
    if($fEnd !== '' && $fEnd < $time) {
      $previous[] = $flight;
    } elseif($fStart !== '' && $fStart > $time) {
      $upcoming[] = $flight;
    } else {
      $ongoing[] = $flight;
    }
  }
?>
<section>
  <div class="col-sm-12 text-center">
      <h3> On Going Events: </h3>
  </div>
  <div class="events-wrap container-fluid">
    <?php if(count($ongoing) === 0): ?>
      <p class="text-center text-muted">No ongoing events.</p>
    <?php endif; ?>
    <?php foreach($ongoing as $flight): ?>
      <?php
        $sStart = SecurityHelper::safeDecode($flight->starttime);
        $sEnd   = SecurityHelper::safeDecode($flight->endtime);
        $timeDisplay = (isset($sStart['starttime']) ? $sStart['starttime'] : 'N/A') . ' - ' . (isset($sEnd['endtime']) ? $sEnd['endtime'] : 'N/A');
        $dateDisplay = isset($sStart['startdate']) ? $sStart['startdate'] : 'N/A';
      ?>
      <div class="event-item col-sm-6 col-xs-12 col-md-3">
          <div class="spacer col-xs-12">
              <h2 class="event-heading"><a href="events/{{ $flight->code }}">{{ $flight->name }}</a></h2>
              <p><strong>Description: </strong><div class="event-intro">{!! $flight->description !!}</div></p>
              <p class="text-center">
                  <strong><span class="fa fa-clock-o"></span> {{ $timeDisplay }}</strong>
              </p>
              <p class="read-more text-center">
                <a href="events/{{ $flight->code }}" class="btn">Review</a>
              </p>
              <div class="clearfix">
                  <p class="pull-left">By <a href="#">{{ $flight->uploaded_by }}</a></p>
                  <p class="pull-right"><i class="fa fa-calendar-o"></i> {{ $dateDisplay }}</p>
              </div>
          </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="col-sm-12 text-center">
      <h3> Upcoming Events: </h3>
  </div>
  <div class="events-wrap container-fluid">
    <?php if(count($upcoming) === 0): ?>
      <p class="text-center text-muted">No upcoming events.</p>
    <?php endif; ?>
    <?php foreach($upcoming as $flight): ?>
      <?php
        $uStart = SecurityHelper::safeDecode($flight->starttime);
        $uEnd   = SecurityHelper::safeDecode($flight->endtime);
        $uTimeDisplay = (isset($uStart['starttime']) ? $uStart['starttime'] : 'N/A') . ' - ' . (isset($uEnd['endtime']) ? $uEnd['endtime'] : 'N/A');
        $uDateDisplay = isset($uStart['startdate']) ? $uStart['startdate'] : 'N/A';
      ?>
      <div class="event-item col-sm-6 col-xs-12 col-md-3">
          <div class="spacer col-xs-12">
              <h2 class="event-heading"><a href="events/{{ $flight->code }}">{{ $flight->name }}</a></h2>
              <p><strong>Description: </strong><div class="event-intro">{!! $flight->description !!}</div></p>
              <p class="text-center">
                  <strong><span class="fa fa-clock-o"></span> {{ $uTimeDisplay }}</strong>
              </p>
              <p class="read-more text-center">
                <a href="events/{{ $flight->code }}" class="btn">Review</a>
              </p>
              <div class="clearfix">
                  <p class="pull-left">By <a href="#">{{ $flight->uploaded_by }}</a></p>
                  <p class="pull-right"><i class="fa fa-calendar-o"></i> {{ $uDateDisplay }}</p>
              </div>
          </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="col-sm-12 text-center">
      <h3> Previous Events: </h3>
  </div>
  <div class="events-wrap container-fluid">
    <?php if(count($previous) === 0): ?>
      <p class="text-center text-muted">No previous events.</p>
    <?php endif; ?>
    <?php foreach($previous as $pre): ?>
      <?php
        $pStart = SecurityHelper::safeDecode($pre->starttime);
        $pEnd   = SecurityHelper::safeDecode($pre->endtime);
        $pTimeDisplay = (isset($pStart['starttime']) ? $pStart['starttime'] : 'N/A') . ' - ' . (isset($pEnd['endtime']) ? $pEnd['endtime'] : 'N/A');
        $pDateDisplay = isset($pStart['startdate']) ? $pStart['startdate'] : 'N/A';
      ?>
      <div class="event-item col-sm-6 col-xs-12 col-md-3">
          <div class="spacer col-xs-12">
              <h2 class="event-heading"><a href="events/{{ $pre->code }}">{{ $pre->name }}</a></h2>
              <p><strong>Description: </strong><div class="event-intro">{!! $pre->description !!}</div></p>
              <p class="text-center">
                  <strong><span class="fa fa-clock-o"></span> {{ $pTimeDisplay }}</strong>
              </p>
              <p class="read-more text-center">
                <a href="events/{{ $pre->code }}" class="btn">Review</a>
              </p>
              <div class="clearfix">
                  <p class="pull-left">By <a href="#">{{ $pre->uploaded_by }}</a></p>
                  <p class="pull-right"><i class="fa fa-calendar-o"></i> {{ $pDateDisplay }}</p>
              </div>
          </div>
      </div>
    <?php endforeach; ?>
  </div>
  <!-- events wrap end -->
</section>
