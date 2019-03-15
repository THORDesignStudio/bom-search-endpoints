<?php
  /**
   * DATE CONVERTER
   * 
   * Sniffs out what the issue date is from the tax tag
   * Issue dates conform to the format `Month YYYY`, sometimes they are `Month/Month YYYY`
   * Not easy to deal with, and the user can really put anything in there, so...we know they will
   *
   * @param issue_date|string Name of the taxonomy tag, string
   * @return ISO_date|string Date of issue as ISO YYYY-MM-DD, string
   */   
  function date_conversion( $issue_date ) {
    // Set some variables for day, month and year
    $day = "01"; // day will always just be first of the month, we don't have day specific values
    $month = null; 
    $year = null;

    // Break issue_date up into month and year, hopefully
    $separated_dates = explode(" ", $issue_date);

    // Year will always start 2XXX, so this should work - otherwise it's a month
    // Convert to lowercase on everything to match month sniffing easier
    foreach($separated_dates as $separated_date) {
      if (substr($separated_date, 0, 1) == '2') {
        $year = strtolower($separated_date);
      } else {
        $month = strtolower($separated_date);
      } 
    }

    // Sniff month | month set options, do manual reformat
    switch ($month) {
      case "january/february":
        $month = "01";
        break;
      case "january":
        $month = "01";
        break;
      case "february":
        $month = "02";
        break;
      case "march":
        $month = "03";
        break;
      case "april":
        $month = "04";
        break;
      case "may":
        $month = "05";
        break;
      case "june":
        $month = "06";
        break;
      case  "july/august":
        $month = "07";
        break;
      case "july":
        $month = "07";
        break;        
      case "august":
        $month = "08";
        break;
      case "september":
        $month = "09";
        break;
      case "october":
        $month = "10";
        break;
      case "november":
        $month = "11";
        break;
      case "december":
        $month = "12";
        break;
      default:
        $month = "01";
    }
  }

  // concat it back to together YYYY-MM-DD
  return $year . '-' . $month . '-' . $day;
?>