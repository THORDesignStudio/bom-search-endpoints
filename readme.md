# Business Officer Search Endpoint API
====
Custom post-type API extension of the [WordPress REST API](https://developer.wordpress.org/rest-api/) for the [Business Officer website](https://www.businessofficermagazine.org/)


## Installation
====
1. Copy the `bom-search-endpoints` folder into your `wp-content/plugins` folder
2. Activate the `BOM Search Endpoints` plugin via the plugin admin page (please note this plugin will not work without the `Business Officer Magazine Custom Plugin`)


## Endpoints
====

| Endpoint | READ | WRITE | Request
|----------|:--------:|:--------:|:--------:|
| /wp-json/bom/v1/business-intel | ![yes] | ![no] | GET |
| /wp-json/bom/v1/departments | ![yes] | ![no] | GET |
| /wp-json/bom/v1/features | ![yes] | ![no] | GET |
| /wp-json/bom/v1/nacubo-notes | ![yes] | ![no] | GET |

Params:
* These endpoints require no arguments; just hit them and they will return all results for that post-type.

The endpoints map to the 4 main custom post-types in the BOM WordPress install. They are similar yet different data models so each deserves its own endpoint. `features` and `departments` have similar data models, as do `business-intel` and `nacubo-notes`.

Note: on the BOM website there are [5 departments](https://www.businessofficermagazine.org/department/), which includes all content in the `departments`, `nacubo-notes` and `business-intel` endpoints. We have exposed `category` metadata on each endpoint that correspond to the actual site structure a bit better than the underlying data models do. This may determine how you want to display the final results.


## Environments
====
Business Officer Magazine is hosted on [Pantheon](https://pantheon.io/), which automatically creates three environments for their sites. BOM uses a local development environment, which turns DEV into a staging environment.

* DEV / STAGING: [https://dev-business-officer-magazine.pantheonsite.io/](https://dev-business-officer-magazine.pantheonsite.io/)
* PROD: [https://www.businessofficermagazine.org](https://www.businessofficermagazine.org)

These endpoints can be test against DEV and when approved, will promote up to production.


## Namespace
===
The full namespace of the API:
* `/wp-json/bom/v1`
 
This plugin extends WP-API built into WordPress to create these endpoints. Read more here if you're interested: https://developer.wordpress.org/rest-api/

We're using WP_REST_Controller which automatically puts endpoints at `/wp-json`. We extend that with our own namespace of `bom` and then version with `v1`. You can't change `/wp-json` and should not change `bom`. 

However, `v1` can be changed inside of each endpoint - and should be if you enhance any endpoint.


## Examples
===

### Business Intel

Endpoint: `/wp-json/bom/v1/business-intel` 

Schema:

```json
{
  "nacubo-notes-#": {
    "url": string,
    "title": string,
    "publication-date": string,
    "issue": string,
    "category": string,
    "content-group-#": {
      "subtitle": string,
      "content": string,
    },
    "fast-fact": string,
    "quick-clicks": string
  }
}
````

Response example (long text strings are truncated in this example):

```json
{
  "business-intel-1": {
    "url": "https:\/\/businessofficermagazine.lndo.site\/business-intel\/business-intel-april-2016\/",
    "title": "Business Intel, April 2016",
    "publication-date": "2018-05-01",
    "issue": "April 2016",
    "category": "business-intel",
    "content-group-1": {
      "subtitle": "CAMPUS SAFETY",
      "content": "It is 2 a.m., ..."
    },
    "fast-fact": " \u201cOn the test for digital problem-solving skills, ...",
    "quick-clicks": "State Funding Shows Slow Recovery State..."
  },
  "business-intel-2": {
    "url": "https:\/\/businessofficermagazine.lndo.site\/business-intel\/business-intel-april-2017\/",
    "title": "Business Intel, April 2017",
    "publication-date": "2017-04-01",
    "issue": "April 2017",
    "category": "business-intel",
    "content-group-1": {
      "subtitle": "Technology",
      "content": "The sixth annual 2017 State of \u201d ..."
    },
    "content-group-2": {
      "subtitle": "Organizational Effectiveness",
      "content": "How does a small Christian university increase its enrollment by 57 percent in only two years? For Regent University, Virginia Beach, Va., it was all about launching a major strategic growth initiative in 2015, calling for a significant investment in new programs and student services.\nAccording to Regent\u2019s chancellor and CEO, M.G. Robertson, \u201cBy investing in new programs and services, with a major focus on new technology, Regent is now much better positioned for sustainable growth, while at the same time continuing to offer our students a quality education that is also affordable.\u201d With enrollment setting a new all-time high of 10,000 students, the university is also achieving unprecedented undergraduate retention rates that are \u201cupwards of 80 percent,\u201d reports Gerson Moreno-Ria\u00f1o, executive vice president for academic affairs and dean of the college of arts and sciences.\nUpping its online presence. Ranked among the nation\u2019s top online schools by U.S. News &amp; World Report and other sources, Regent has increased its distance learning offerings to more than 110 online degree programs at both the undergraduate and graduate levels.\nIntegrating student support systems. The university has brought together a number of units to increase the quality of the student experience, including university advising, the center for student happiness, and the academic support center. These integrated programs provide such services as free success coaching; 24\/7 technical, resource, and prayer support; academic tutoring; and workshops on success topics.\nLater this year, Regent plans to implement more new programs and services as a continuation of its strategic growth initiative."
    },
    "fast-fact": "\" \u2026 15- to 24-year-olds are spending 15 pe ...",
    "quick-clicks": "What\u2019s Up With Student Performance? A recently..."
  },
}
```

===

### Departments

Endpoint: `/wp-json/bom/v1/departments`

Schema:

```json
{
  "department-item-#": {
    "url": string,
    "title": string,
    "subtitle": string,
    "publication-date": string,
    "issue": string,
    "category": string,
    "author": string,
    "topic-group": {
      "topic-#": string
    },
    "content": string,
    "sidebar-group-#": {
      "subtitle": string,
      "content": string
    },
    "pullquote-group-#": {
      "content": string,
      "author": string,
    },
  }
}
```

Response example (long text strings are truncated in this example):

```json
{
 "departments-88": {
    "url": "https:\/\/businessofficermagazine.lndo.site\/departments\/wellness-center-promotes-all-types-of-fitness\/",
    "title": "Wellness Center Promotes All Types of Fitness",
    "subtitle": "Wellness Center Promotes All Types of Fitness",
    "publication-date": "2018-10-12",
    "issue": "February 2016",
    "category": "Vantage Point",
    "author": "By Robert D. Flanigan Jr. and Darnita R. Killian",
    "content": "Four years ago, Spelman College, Atlanta, received...",
    "pullquote-group-1": {
      "content": "After receiving the NCAA notice, we decided not to...",
      "author": ""
    }
  },
  "departments-89": {
    "url": "https:\/\/businessofficermagazine.lndo.site\/departments\/where-we-are-with-higher-education-policy\/",
    "title": "Where We Are With Higher Education Policy",
    "subtitle": "Where We Are With Higher Education Policy",
    "publication-date": "2018-04-11",
    "issue": "April 2018",
    "category": "Advocacy and Action",
    "author": "By Bryan Dickson",
    "content": "Throughout 2017, both the House Education and the Workforce, and...",
    "sidebar-group-1": {
      "subtitle": "Prepare Now to Comment on Upcoming Proposals",
      "content": "Last year, tax reform sped through Congress in less than two months..."
    },
    "pullquote-group-1": {
      "content": "The PROSPER Act would change the way federal loans and grants are...",
      "author": ""
    },
    "pullquote-group-2": {
      "content": "In a reverse of current policy, Title IV funds returned to ED would go",
      "author": ""
    }
  }
}
```

===

### Features

Endpoint: `/wp-json/bom/v1/features` 

Schema:

```json
{
  "feature-item-#": {
    "url": string,
    "title": string,
    "subtitle": string,
    "publication-date": string,
    "issue": string,
    "category": string,
    "author": string,
    "topic-group": {
      "topic-#": string
    },
    "content": string,
    "sidebar-group-#": {
      "subtitle": string,
      "content": string
    },
    "pullquote-group-#": {
      "content": string,
      "author": string,
    },
  }
}
````

Response example (long text strings are truncated in this example):

```json
{
  "features-188": {
    "url": "https:\/\/businessofficermagazine.lndo.site\/features\/whats-the-story-in-socal\/",
    "title": "What\u2019s the Story in SoCal?",
    "subtitle": "What\u2019s the Story in SoCal?",
    "publication-date": "2018-04-11",
    "issue": "April 2018",
    "category": "features",
    "author": "By Khesia Taylor",
    "content": "Nestled between the southeastern corner of Los Angeles County...",
    "sidebar-group-1": {
      "subtitle": "Corporate Showcases Enhance the Overall Program",
      "content": "Six corporate showcases offer new solutions to..."
    },
    "sidebar-group-2": {
      "subtitle": "Leadership Preconference Workshops",
      "content": "Immersive and interactive preconference workshops ..."
    },
    "sidebar-group-3": {
      "subtitle": "Meet the First Registrant",
      "content": "Since 2014, Michael McKee has served as vice president ..."
    },
    "sidebar-group-4": {
      "subtitle": "Earn CPE Credits",
      "content": "Participants of the NACUBO 2018 Annual Meeting will..."
    },
    "sidebar-group-5": {
      "subtitle": "Gruwell Inspires Innovation and Change",
      "content": "Erin Gruwell, inspirational educator and author, has..."
    },
    "sidebar-group-6": {
      "subtitle": "Creativity and the Chief Business Officer",
      "content": "NACUBO designed this year\u2019s annual meeting with..."
    }
  },
  "features-189": {
    "url": "https:\/\/businessofficermagazine.lndo.site\/features\/when-activism-rises\/",
    "title": "When Activism Rises",
    "subtitle": "When Activism Rises",
    "publication-date": "2017-09-11",
    "issue": "September 2017",
    "category": "features",
    "author": "By Apryl Motley",
    "topic-group": {
      "topic-1": "Diversity and Inclusion",
      "topic-2": "Leadership",
      "topic-3": "Risk Management"
    },
    "content": "Students on college and university campuses across the nation are...",
    "sidebar-group-1": {
      "subtitle": "Speak Freely, But Not Too Freely",
      "content": "To say that controversial scholar Charles Murray, author of The..."
    },
    "sidebar-group-2": {
      "subtitle": "Education as a Preventative Measure",
      "content": "The image of almost 1,000 young women wearing white dresses as they..."
    },
    "pullquote-group-1": {
      "content": "2016 \u2013 Students at Duke University staged a weeklong sit-in...",
      "author": ""
    },
    "pullquote-group-2": {
      "content": "2012 \u2013 The University of Texas, Austin, establishes the Campus Climate...",
      "author": ""
    },
    "pullquote-group-3": {
      "content": "$50M \u2013 The Division of Diversity and Community Engagement at the...",
      "author": ""
    },
    "pullquote-group-4": {
      "content": "\u201cIt\u2019s really important that you empower complainants, and they...",
      "author": "Gregory Vincent, University of Texas, Austin"
    }
  },
}
```

===

### Nacubo Notes

Endpoint: `/wp-json/bom/v1/nacubo-notes` 

Schema:

```json
{
  "nacubo-notes-#": {
    "url": string,
    "title": string,
    "publication-date": string,
    "issue": string,
    "category": string,
    "content-group-#": {
      "subtitle": string,
      "content": string,
    },
  }
}
````

Response example (long text strings are truncated in this example):

```json
{
  "nacubo-notes-25": {
    "url": "https:\/\/businessofficermagazine.lndo.site\/nacubo-notes\/nacubo-notes-november-2017\/",
    "title": "NACUBO Notes, November 2017",
    "publication-date": "2017-11-12",
    "issue": "November 2017",
    "category": "nacubo-notes",
    "content-group-1": {
      "subtitle": "All Good Things \u2026",
      "content": "John Walda (right), with Randy Gentzler, NACUBO board chair..."
    },
    "content-group-2": {
      "subtitle": "On-Demand: Updates From Federal Affairs",
      "content": "Distance learning webcasts and live streaming programs continue ..."
    },
    "content-group-3": {
      "subtitle": "Advancing the Agenda",
      "content": "NACUBO President and CEO John Walda (back row, sixth from left); NACUBO..."
    },
    "content-group-4": {
      "subtitle": "On-Demand Learning Opportunities",
      "content": "Although the NACUBO 2017 Annual Meeting is behind us, several of the..."
    }
  },
  "nacubo-notes-26": {
    "url": "https:\/\/businessofficermagazine.lndo.site\/nacubo-notes\/nacubo-notes-october-2016\/",
    "title": "NACUBO Notes, October 2016",
    "publication-date": "2018-04-10",
    "issue": "October 2016",
    "category": "nacubo-notes",
    "content-group-1": {
      "subtitle": "NACUBO\u2019s Latest in Distance Learning",
      "content": "Distance learning allows attendees to participate in virtual programs..."
    },
    "content-group-2": {
      "subtitle": "Key Facilities Metrics Survey Now Open",
      "content": "Campus financial leaders can recite pertinent campus facts, such as cost..."
    },
    "content-group-3": {
      "subtitle": "<em>Business Officer<\/em> Turns 50",
      "content": "Business Officer magazine begins its 50th year of publication with the July\/August..."
    },
    "content-group-4": {
      "subtitle": "<em>Business Officer<\/em> Wins Annual AM&P Awards",
      "content": "The Business Officer magazine team celebrates during this year\u2019s AM&amp;P annual..."
    }
  },
}
```
