<?php
  use App\Helpers\SecurityHelper;
  $previous = [];
?>
<section>
  <div class="col-sm-12 text-center">
      <h3> On Going Events: </h3>
  </div>
  <div class="events-wrap container-fluid">

        <!-- Events Listing Start Here -->
          <?php
          foreach($programList as $flight)
          {
            date_default_timezone_set('Asia/Kolkata');
            $time = date("YmdHi", time());
            if(isset($flight->end) && $flight->end < $time) {
              $previous[] = $flight;
            } else {
              $sStart = SecurityHelper::safeDecode($flight->starttime);
              $sEnd = SecurityHelper::safeDecode($flight->endtime);
              $timeDisplay = (isset($sStart['starttime']) ? $sStart['starttime'] : '') . ' - ' . (isset($sEnd['endtime']) ? $sEnd['endtime'] : '');
              $dateDisplay = isset($sStart['startdate']) ? $sStart['startdate'] : '';
            ?>
        <div class="event-item col-sm-6 col-xs-12 col-md-3">
            <div class="spacer col-xs-12">
                <h2 class="event-heading"><a href="events/{{ $flight->code }}">{{ $flight->name }} </a></h2>

                <p><strong> Description: </strong><div class="event-intro"> {!!$flight->description!!}</div></p>
                <p class="text-center">
                    <strong> <span class="fa fa-clock-o"></span> {{ $timeDisplay }} </strong>
                </p>
                <p class="read-more text-center">
                  <a href="events/{{ $flight->code }}" class="btn">Review </a>
                </p>
                <div class="clearfix">
                    <p class="pull-left">By <a href="#">{{ $flight->uploaded_by }}</a></p>
                    <p class="pull-right"><i class="fa fa-calendar-o"></i> {{ $dateDisplay }}</p>
                </div>
            </div>
        </div>
        <!-- event item end -->
        <?php
            }
          }
        ?>

  </div>
  <div class="col-sm-12 text-center">
      <h3> Previous Events: </h3>
  </div>
  <div class="events-wrap container-fluid">

        <!-- Events Listing Start Here -->
          <?php
          foreach($previous as $pre)
          {
            $pStart = SecurityHelper::safeDecode($pre->starttime);
            $pEnd = SecurityHelper::safeDecode($pre->endtime);
            $pTimeDisplay = (isset($pStart['starttime']) ? $pStart['starttime'] : '') . ' - ' . (isset($pEnd['endtime']) ? $pEnd['endtime'] : '');
            $pDateDisplay = isset($pStart['startdate']) ? $pStart['startdate'] : '';
          ?>
        <div class="event-item col-sm-6 col-xs-12 col-md-3">
            <div class="spacer col-xs-12">
                <h2 class="event-heading"><a href="events/{{ $pre->code }}">{{ $pre->name }} </a></h2>

                <p><strong> Description: </strong><div class="event-intro"> {!!$pre->description!!}</div></p>
                <p class="text-center">
                    <strong> <span class="fa fa-clock-o"></span> {{ $pTimeDisplay }} </strong>
                </p>
                <p class="read-more text-center">
                  <a href="events/{{ $pre->code }}" class="btn">Review </a>
                </p>
                <div class="clearfix">
                    <p class="pull-left">By <a href="#">{{ $pre->uploaded_by }}</a></p>
                    <p class="pull-right"><i class="fa fa-calendar-o"></i> {{ $pDateDisplay }}</p>
                </div>
            </div>
        </div>
        <!-- event item end -->
        <?php
          }
        ?>

  </div>
  <!-- events wrap end -->
</section>
