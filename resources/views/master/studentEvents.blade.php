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
              <div class="event-item col-xs-12 col-sm-6 col-md-3">
                  <div class="spacer col-xs-12">
                      <h2 class="event-heading"><a href="contest/{{ $flight->code }}">{{ $flight->name }} </a></h2>

                      <p> <strong> Description: </strong><div class="event-intro">{!!$flight->description!!}</div></p>
                      <p class="text-center">
                          <strong> <span class="fa fa-clock-o"></span> {{ $timeDisplay }} </strong>
                      </p>
                      <p class="read-more text-center "><a href="contest/{{ $flight->code }}" class="btn btn-template-main">Participate </a></p>
                      <div class="clearfix">
                          <p class="pull-left">By <a href="#">{{ $flight->uploaded_by }}</a></p>
                          <p class="pull-right"><span class="fa fa-calendar-o"></span> {{ $dateDisplay }}</p>
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

        <div class="event-item col-xs-12 col-sm-6 col-md-3">
            <div class="spacer col-xs-12">
                <h2 class="event-heading"><a href="contest/{{ $pre->code }}">{{ $pre->name }} </a></h2>

                <p><strong> Description: </strong><div class="event-intro"> {!!$pre->description!!}</div></p>
                <p class="text-center">
                    <strong> <span class="fa fa-clock-o"></span> {{ $pTimeDisplay }} </strong>
                </p>
                <p class="read-more text-center "><a href="contest/{{ $pre->code }}" class="btn btn-template-main">Participate </a></p>
                <div class="clearfix">
                    <p class="pull-left">By <a href="#">{{ $pre->uploaded_by }}</a></p>
                    <p class="pull-right"><span class="fa fa-calendar-o"></span> {{ $pDateDisplay }}</p>
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
