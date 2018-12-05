# Business Officer Search Endpoint API
Custom post-type API extension of the [WordPress REST API](https://developer.wordpress.org/rest-api/) for the [Business Officer website](https://www.businessofficermagazine.org/)


## Installation
1. Copy the `bom-search-endpoints` folder into your `wp-content/plugins` folder
2. Activate the `BOM Search Endpoints` plugin via the plugin admin page (please note this plugin will not work without the `Business Officer Magazine Custom Plugin`)


## Endpoints

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
Business Officer Magazine is hosted on [Pantheon](https://pantheon.io/), which automatically creates three environments for their sites. BOM uses a local development environment, which turns DEV into a staging environment.

* DEV / STAGING: [https://dev-business-officer-magazine.pantheonsite.io/](https://dev-business-officer-magazine.pantheonsite.io/)
* PROD: [https://www.businessofficermagazine.org](https://www.businessofficermagazine.org)

These endpoints can be test against DEV and when approved, will promote up to production.


## Namespace
The full namespace of the API:
* `/wp-json/bom/v1`
 
This plugin extends WP-API built into WordPress to create these endpoints. Read more here if you're interested: https://developer.wordpress.org/rest-api/

We're using WP_REST_Controller which automatically puts endpoints at `/wp-json`. We extend that with our own namespace of `bom` and then version with `v1`. You can't change `/wp-json` and should not change `bom`. 

However, `v1` can be changed inside of each endpoint - and should be if you enhance any endpoint.


## Examples

### Business Intel

Endpoint: `/wp-json/bom/v1/business-intel` 

Schema:

```
{
  "business-intel-##": {
    "abstract": null,
    "author": null,
    "category": string,
    "content": null,
    "image": null,
    "issue": string,
    "modified-date": string,
    "publication-date": string,
    "title": string,
    "topic-group": null,
    "url": string,
    "content-group-#": {
      "abstract": string,
      "author": string,
      "category": string,
      "content": string,
      "image-url": string,
      "issue": string,
      "modified-date": string,
      "publication-date": string,
      "title": string,
      "topic-group": {
        "topic-1": string
      },
      "url": string
    },
    "fast-fact": {
      "abstract": string,
      "author": string,
      "category": string,
      "content": string,
      "image": null,
      "issue": string,
      "modified-date": string,
      "publication-date": string,
      "title": string,
      "topics": null,
      "url": string
    },
    "quick-clicks": {
      "abstract": string,
      "author": string,
      "category": string,
      "content": string,
      "image": null,
      "issue": string,
      "modified-date": string,
      "publication-date": string,
      "title": string,
      "topics": null,
      "url": string,
    }  
  }
}
````

Response example (long text strings are truncated in this example):

```json
{
  "business-intel-27": {
    "abstract": null,
    "author": null,
    "category": "business-intel",
    "content": null,
    "image": null,
    "issue": "November 2018",
    "modified-date": "2018-11-08",
    "publication-date": "2018-11-08",
    "title": "November 2018",
    "topic-group": null,
    "url": "https:\/\/businessofficermagazine.lndo.site\/business-intel\/november-2018\/",
    "content-group-1": {
      "abstract": "What keeps you up at night? While the immediate top-of-mind response to that question may vary widely among chief business officers, the answer centers on the ever-present risk management concerns, such as an international incident involving students, a c ...",
      "author": "NACUBO Editorial Team",
      "category": "business-intel",
      "content": "What keeps you up at night? While the immediate top-of-mind response to that question may vary widely among chief business officers, the answer centers on the ever-present risk management concerns, such as an international incident involving students, a cybersecurity threat, or a costly compliance breach.\nThe University Risk Management and Insurance Association (URMIA) risk inventory identifies 23 separate categories\u2014from athletics to intellectual property to public safety\u2014that may present a risk to an institution in a variety of ways, including strategic, operational, or reputational. While the list of highest-level concerns is largely similar across all institution types, the particular nuance of those concerns will vary from one college or university to another based on various factors, including institution mission.\nNo matter the threat, the job of an institution risk manager is to mitigate the potential adverse impacts of a program or activity, according to presenters of the session \u201cHot Topics in Risk Management That the CFO Needs to Know,\u201d who spoke at the NACUBO 2018 Annual Meeting.\nIdentify risk. According to Luke Figora, Northwestern University\u2019s associate vice president for risk management and environmental health and safety, topping the list of concerns for his senior vice president and CFO are HIPAA compliance and data security breaches, concussions and potential litigation and liability surrounding brain injuries, and student mental health.\nTo address these ongoing concerns, Northwestern has developed an extensive concussion management program that includes baseline testing for all athletes that is repeated in a student\u2019s third and fifth years, a traumatic brain injury specialist athletic trainer on staff dedicated solely to this issue, a concussion care team, and concussion education requirements and protocols that extend to university club and intramural sports.\nCommunicate risk. Enrollment ranks as No. 4 on a list of top concerns at Nebraska Wesleyan University, according to Tish Gade-Jones, vice president for finance and administration. The institution\u2019s proactive approach to maintaining healthy enrollment numbers includes developing a mission-driven admissions team with a focus on early recruitment, structuring meaningful first-year experiences for students, strengthening partnerships with community colleges, and developing themed campus visits for prospective students based on their curricular interests in art, nursing, and so forth.\nThat said, even when your institution is strategically focused on maintaining enrollment as a core priority, any possibility for lasting reputational risk that would counter those efforts rises to the top. Such a possibility for reputational risk was underscored in October 2017, when a gas leak that occurred off campus led to panic and misinformation across campus. This incident highlighted the risk of not having a focused communication response plan.\nFrom a fire alarm going off in the library to helicopters flying over campus, ad hoc social media chatter exacerbated rather than calmed concerns. In the aftermath of that particular incident, the university instituted changes to (1) ensure that one point person was responsible for communicating with police and first responders, (2) designate an official text message system for university communications, and (3) better train students on social media use related to campus activity.\nPrioritize risk. For Cheryl Lloyd, the University of California System\u2019s associate vice president and chief risk officer, deferred maintenance and capital renewal are among her top concerns, based in part on polling CBOs from the 10 campuses and five medical centers that comprise the UC System. Left unaddressed, festering deferred maintenance issues spread across such a large portfolio can carry a huge risk related to workers\u2019 compensation, not to mention reputational risk and risk to ratings from financial agencies that would only exacerbate the ability of the institution to secure ongoing funding.\nTo stay on top of these issues, UC created its Integrated Concept and Implementation Plan (ICAMP) to inventory and rank the system\u2019s deferred maintenance priorities. The plan will help provide granular detail for each building on each campus to capture the backlog of architectural, electrical, and mechanical needs, while assessing the priority of projects based on a building\u2019s use and importance to mission.\nThe software program UC is using to develop its plan will provide a fair, credible, and justifiable way to prioritize projects based on urgency on each campus and across all campuses. For instance, issues can be flagged for immediate replacement or replacement in one to three years, or three to five years. A mobile app synced with the system\u2019s facility and space database allows for continual updating of information. While mapping the entire system will take some time, Lloyd says that, in the end, this building-by-building snapshot will greatly enhance the system\u2019s ability to keep its deferred maintenance risk in check.\nKARLA HIGNITE, Fort Walton Beach, Fla., is a contributing editor for\u00a0Business Officer.",
      "image-url": null,
      "issue": "November 2018",
      "modified-date": "2018-11-08",
      "publication-date": "2018-11-08",
      "title": "Handling Risks Head-On",
      "topic-group": {
        "topic-1": null
      },
      "url": "https:\/\/businessofficermagazine.lndo.site\/business-intel\/november-2018\/#handling-risks-head-on"
    },
    "content-group-2": {
      "abstract": "The Graduate Enrollment and Degrees: 2007 to 2017 report includes data and trends on applications for admission to graduate school, first-time and total graduate student enrollment, and graduate degrees conferred.\nThe report has been published annually si ...",
      "author": "NACUBO Editorial Team",
      "category": "business-intel",
      "content": "The Graduate Enrollment and Degrees: 2007 to 2017 report includes data and trends on applications for admission to graduate school, first-time and total graduate student enrollment, and graduate degrees conferred.\nThe report has been published annually since 1986 and is a joint project of the Council of Graduate Schools (CGS) and the Graduate Record Examinations (GRE) Board.\nSee below for the By the Numbers infographic. **Source: CGS\/GRE 2017 Graduate Enrollment and Degrees. Online at https:\/\/cgsnet.org\/graduate-entrollment-and-degrees.\n&nbsp;\n&nbsp;",
      "image-url": null,
      "issue": "November 2018",
      "modified-date": "2018-11-08",
      "publication-date": "2018-11-08",
      "title": "2017 CGS\/GRE Survey of Graduate Enrollment",
      "topic-group": {
        "topic-1": "By The Numbers"
      },
      "url": "https:\/\/businessofficermagazine.lndo.site\/business-intel\/november-2018\/#2017-cgsgre-survey-of-graduate-enrollment"
    },
    "fast-fact": {
      "abstract": "By 2022, 75 million current jobs will be displaced by the shift in the division of labor between humans, machines, and algorithms. \u2013The Future of Jobs Report 2018 ...",
      "author": "NACUBO Editorial Team",
      "category": "business-intel",
      "content": "By 2022, 75 million current jobs will be displaced by the shift in the division of labor between humans, machines, and algorithms. \u2013The Future of Jobs Report 2018",
      "image": null,
      "issue": "November 2018",
      "modified-date": "2018-11-08",
      "publication-date": "2018-11-08",
      "title": "November 2018 - Fast Fact",
      "topics": null,
      "url": "https:\/\/businessofficermagazine.lndo.site\/business-intel\/november-2018\/#fast-fact"
    },
    "quick-clicks": {
      "abstract": "Students With \u2018Good\u2019 Jobs Upon Graduation Earn More Recent college graduates who got a \u201cgood\u201d job immediately upon graduation earn considerably higher salaries\u2014over both the short and long term\u2014than graduates who took longer to land a first ? ...",
      "author": "NACUBO Editorial Team",
      "category": "business-intel",
      "content": "Students With \u2018Good\u2019 Jobs Upon Graduation Earn More Recent college graduates who got a \u201cgood\u201d job immediately upon graduation earn considerably higher salaries\u2014over both the short and long term\u2014than graduates who took longer to land a first \u201cgood\u201d job out of college, according to the Strada-Gallup Alumni Survey. Graduates who secured a \u201cgood\u201d job\u2014as self-defined by the respondents\u2014upon graduation were 2.4 times more likely to earn $60,000 or more in personal income today than graduates who took two to less than 12 months to land a \u201cgood\u201d job after graduation. Additionally, more than a third (38 percent) of recent college graduates who took a year or more to land a \u201cgood\u201d job now earn less than $24,000 in personal income. Americans Lag in Learning a Foreign Language Learning a foreign language is a nearly ubiquitous experience for students throughout Europe, driven in part by the fact that most European countries have national-level mandates for formally studying languages in school. No such national standard exists in the U.S., where requirements are mostly set at the school district or state level. Overall, a median of 92 percent of European students are learning a language in school. Meanwhile, throughout all 50 U.S. states and the District of Columbia, 20 percent of K\u201312 students are enrolled in foreign language classes, according to a 2017 report from the nonprofit American Councils for International Education. Mixed emphasis on language study may reflect Americans\u2019 perceptions of what skills are necessary for workers today.",
      "image": null,
      "issue": "November 2018",
      "modified-date": "2018-11-08",
      "publication-date": "2018-11-08",
      "title": "November 2018 - Quick Clicks",
      "topics": null,
      "url": "https:\/\/businessofficermagazine.lndo.site\/business-intel\/november-2018\/#quick-clicks"
    }
  }
}
```

===

### Departments

Endpoint: `/wp-json/bom/v1/departments`

Schema:

```
{
  "department-item-#": {
    "abstract": string,
    "author": string,
    "category": string,
    "content": string,
    "image-url": srting,
    "issue": string,
    "modified-date": string,
    "publication-date": string,
    "title": string,
    "topic-group": {
      "topic-1": string
    },
    "url": string,
    "sidebar-group-#": {
      "content": string,
      "title": string
    },
    "pullquote-group-#": {
      "author": string,
      "content": string,
    },
  }
}
```

Response example (long text strings are truncated in this example):

```json
{
 "departments-77": {
    "abstract": "In the spring of 2015, the chairs from several of Macalester College\u2019s academic departments asked for a meeting with the president, the provost, and the chief financial officer. The chairs wanted to discuss increasingly serious overcrowding in the Olin- ...",
    "author": "By David Wheaton",
    "category": "Vantage Point",
    "content": "In the spring of 2015, the chairs from several of Macalester College\u2019s academic departments asked for a meeting with the president, the provost, and the chief financial officer. The chairs wanted to discuss increasingly serious overcrowding in the Olin-Rice Science Center, which houses the physical sciences plus psychology.\u00a0\nThe building had been renovated in 1997, when 18 percent of students had a major based there. Thanks to a rapidly growing demand for STEM courses, however, 48 percent of students had a major based in the science center by 2016.\u00a0\nThat increase in STEM enrollments was impacting the quality of instruction, as well as the faculty\u2019s ability to deliver courses, meet with students, and have appropriate office space. To accommodate the number of courses needed to meet student demand, for example, some classes were assigned to classrooms designed for enrollments and teaching methods that did not reflect current needs. Some adjunct faculty needed to share office space. In addition, meeting with students had become a challenge when a professor\u2019s office hours attracted multiple students simultaneously\u2014the science center did not have any shared space to accommodate a larger group. \u00a0\nGiven these concerns, the administrative leaders agreed to begin a process that would find ways to alleviate the building\u2019s overcrowding. After preliminary discussions, we realized that limiting the space analysis to one building would be inadequate; solutions to address the rising enrollments in Olin-Rice could easily spill into nearby buildings. If, for example, we permanently relocated an entire department to another nearby building, then another round of analysis would be required for the affected building, and so on.\u00a0\nThe project quickly morphed into a review of space usage in all buildings that house major functions, mainly academic divisions. We then expanded the project\u2019s scope to include non-instructional spaces\u2014student support areas, such as athletics, campus center, library, and administrative offices. (We did not include residence halls because they are essentially single-purpose spaces.) The project was a first for Macalester, which has 2,100 students. Campus master plans had been created in 1968, 1995, and 2005, but those documents proposed where buildings would be located, not how their internal spaces would be used.\nObjective and Subjective\nIt quickly became apparent that we needed outside assistance to capture and analyze both objective and subjective data related to the usage of 1.4 million square feet. The size of the data set required processing and analytical expertise that didn\u2019t reside on campus. We also wanted to benefit from what a third party had seen in other institutions. After interviewing several potential partners, we chose Shepley Bulfinch, an architectural firm from Boston.\nWe began by gathering room assignment information for the previous five years from the campus systems that assigned classrooms (through the registrar) and other reservable public spaces (through the central scheduling system). That information was supplemented with interviews of major space users\u2014functions having a regular need for multiple spaces, such as the registrar and special events planning staff. We also set up tables in high-traffic areas and asked students to share their views about space usage on campus.\nThe data-gathering phase was concentrated in the first month of the project (October). Most of the remaining time\u2014about seven months\u2014was spent analyzing the information, testing possible conclusions, proposing short- and medium-term solutions, and drafting a final report. The results of the space assessment study, which carried few surprises, showed that:\n\nThe amount of unused and underutilized space was relatively small, suggesting that growth in Macalester\u2019s student body size and number of activities hadn\u2019t been accompanied by growth in square footage. In other words, we didn\u2019t discover a trove of places that could be easily transformed into solutions for either academic crowding or other programmatic needs.\nMany classrooms had heavy usage, defined as being assigned more than 55 percent of the theoretically assignable hours from Monday through Friday. Of Macalester\u2019s 52 classrooms, one-third carried a disproportionate share of the load. The remainder were either very specialized or undesirable because of design or location.\u00a0\nIn the evenings, the heaviest usage occurred in only three buildings\u2014the campus center, library, and athletics complex.\u00a0\nMany students reported studying in their residence halls.\nNext to good furniture, the feature students desired most in any meeting or study space was a high number of electrical outlets.\u00a0\n\nA space assessment study\u2014Macalester\u2019s first\u2014helped the college rethink a building renovation and\u00a0repurpose other areas for greater effectiveness.\nNew and Improved\nUndertaking the space assessment project showed us that the data we needed wasn\u2019t always as refined as we\u2019d like. By far, the most precise data showed the assignment of classrooms. We knew where every class met, every semester, for the previous five years because of the systematic way the registrar\u2019s staff assigned spaces, factoring in enrollment limits and the type of teaching pedagogy.\u00a0\nFor instance, certain science lectures require equipment installed in only a few spaces, and some classes require the use of a highly specialized space, such as the ceramics studio, or space with flexible furniture. \u00a0\nWe had much less precise information on the use of public spaces. Staff in Macalester\u2019s campus center handle event scheduling for public spaces that range from a large ballroom to smaller meeting rooms that routinely host dinners, speakers, and receptions for 40 to 100 people, to traditional meeting rooms housing up to 20 people. While these spaces registered heavy usage, the scheduling system often showed only who reserved a space, not the event\u2019s purpose. That realization underscored the challenge of attempting to use data that have one purpose\u2014simply scheduling space\u2014for another: carefully analyzing the varying demands of multiple users.\u00a0\nIn any case, the study clearly identified the need for more\u2014or more functional\u2014classrooms. Rooms designed 50 years ago often don\u2019t support current teaching methods and pedagogies. Their ceiling heights, for example, usually aren\u2019t high enough to allow students in the back to see projected images; many rooms, designed for traditional lecture formats, are difficult to rearrange to accommodate more interactive teaching styles.\u00a0\nTo address this need, we adapted plans already being drawn up for the college\u2019s 53-year-old theater and dance facilities, which needed a complete renovation. The original idea was to add new classroom spaces to the original configuration of the theater and dance building\u2014which was located next to the science center. Instead, we demolished the original building and constructed a new one, along with nine state-of-the-art classrooms that feature flexible furniture, improved sight lines, and enough space to allow for small-group work.\u00a0\nA simple second-story skyway runs between the two buildings, creating a physical and metaphorical connection between the arts and the sciences. The addition of the classrooms allows for valuable repurposing of usable space within the science building\u2019s footprint and possibly some selected repurposing in other academic buildings. For instance, we have reset storage rooms as teaching spaces, turned one underutilized space into an area for two staff members who provide career counseling, and created additional faculty offices.\u00a0\nIn the end, solutions to the most pressing issues came from targeted reuse rather than the addition of space to the most affected building. \u00a0\nSUBMITTED BY David Wheaton, vice president of administration and finance, Macalester College, St. Paul, Minn.\n&nbsp;",
    "image-url": "https:\/\/businessofficermagazine.lndo.site\/wp-content\/uploads\/2018\/06\/BOM_0618_VantagePoint_01-300x150.jpg",
    "issue": "June 2018",
    "modified-date": "2018-06-09",
    "publication-date": "2018-06-09",
    "topic-group": {
      "topic-1": "Facilities, Environmental Compliance"
    },
    "title": "STEM Needs Spur Space Assessment",
    "url": "https:\/\/businessofficermagazine.lndo.site\/departments\/stem-needs-spur-space-assessment\/",
    "pullquote-group-1": {
      "author": null,
      "content": "Solutions came from targeted reuse rather than the addition of space."
    }
  },
  "departments-78": {
    "abstract": "In September 2015, I joined several fellow college and university business officers for NACUBO\u2019s annual Capitol Hill advocacy day. Over the course of the day, we visited with lawmakers and their staff, discussing a number of issues that would later be t ...",
    "author": "By Mary Lou Merkt",
    "category": "Advocacy and Action",
    "content": "In September 2015, I joined several fellow college and university business officers for NACUBO\u2019s annual Capitol Hill advocacy day. Over the course of the day, we visited with lawmakers and their staff, discussing a number of issues that would later be threatened during the debate over tax reform.\nAt [truncated for this example]",
    "image-url": "https:\/\/businessofficermagazine.lndo.site\/wp-content\/uploads\/2018\/06\/BOM_0618_Advocacy_01-300x150.jpg",
    "issue": "June 2018",
    "modified-date": "2018-06-09",
    "publication-date": "2018-06-09",
    "topic-group": {
      "topic-1": "Leadership"
    },
    "title": "Strategic Finance Begins with Advocacy",
    "url": "https:\/\/businessofficermagazine.lndo.site\/departments\/strategic-finance-begins-with-advocacy\/",
    "sidebar-group-1": {
      "content": "Reps. John Delaney (D-MD) and Bradley Byrne (R-AL) are seeking co-sponsors for H.R. 5220\u2014Don\u2019t Tax Higher Education Act. The bipartisan legislation would repeal the new tax on investment income of certain private universities (known as the endowment excise tax), which was signed into law last [truncated for this example]",
      "title": "Lawmakers Seek Co-Sponsors for Don\u2019t Tax Higher Education Act"
    },
    "pullquote-group-1": {
      "author": null,
      "content": "Public and private colleges, and the entire tax-exempt sector, now face unprecedented new tax liabilities."
    }
  }
}
```

===

### Features

Endpoint: `/wp-json/bom/v1/features` 

Schema:

```
{
  "feature-item-#": {
     "abstract": string,
     "author": string,
     "category": string,
     "content": string,
     "image-url": srting,
     "issue": string,
     "modified-date": string,
     "publication-date": string,
     "title": string,
     "topic-group": {
       "topic-1": string
     },
     "url": string,
     "pullquote-group-#": {
       "author": string,
       "content": string
     },
     "sidebar-group-#": {
       "content": string,
       "title": string
     }
  }
}
````

Response example (long text strings are truncated in this example):

```json
{
  "features-182": {
    "abstract": "Most food \u201cwaste\u201d is simply too much of a good thing in the wrong place. In 2011, several students at the University of Maryland, College Park, Md., noticed large amounts of prepared dining hall food not served by the conclusion of the meal ending up  ...",
    "author": "By Karla Hignite",
    "category": "features",
    "content": "Most food \u201cwaste\u201d is simply too much of a good thing in the wrong place. In 2011, several students at the University of Maryland, College Park, Md., noticed large amounts of prepared dining hall food not served by the conclusion of the meal ending up in the trash. By the end of that academic year, [truncated for this example]",
    "image-url": "https:\/\/businessofficermagazine.lndo.site\/wp-content\/uploads\/2018\/08\/BOM_0918_WasteNot_01-300x150.jpg",
    "issue": "September 2018",
    "modified-date": "2018-09-10",
    "publication-date": "2018-09-07",
    "title": "Waste Not, Want Not",
    "topic-group": {
      "topic-1": "Auxiliary Services, Campus Operations"
    },
    "url": "https:\/\/businessofficermagazine.lndo.site\/features\/waste-not-want-not\/",
    "sidebar-group-1": {
      "content": "Food Recovery Network unites students on college campuses to fight food waste and hunger by recovering perishable food that would otherwise go to waste and donating it to people in need. To date, FRN chapters have collectively recovered and donated more than three million pounds of food, equivalent to more [truncated for this example]",
      "title": "Behind the Waste"
    },
    "pullquote-group-1": {
      "author": null,
      "content": "Decreasing food waste at the source and fighting hunger at the local level are the primary aims of this national student-led movement."
    },
    "pullquote-group-2": {
      "author": null,
      "content": "FRN is taking every opportunity to help recover and redirect good food so that it doesn\u2019t go to waste."
    }
  },
  "features-183": {
    "abstract": "All colleges and universities are subject to Environmental Protection Agency (EPA) regulations and must manage their compliance efforts in one way or another. While campuses vary greatly in both the amount of regulation they are subject to and the efforts ...",
    "author": "By Megan Schneider",
    "category": "features",
    "content": "All colleges and universities are subject to Environmental Protection Agency (EPA) regulations and must manage their compliance efforts in one way or another. While campuses vary greatly in both the amount of regulation they are subject to and the efforts they have in place to ensure compliance, many business [truncated for this example]",
    "image-url": "https:\/\/businessofficermagazine.lndo.site\/wp-content\/uploads\/2017\/06\/BOM_1216_EPA_01-300x150.jpg",
    "issue": "December 2016",
    "modified-date": "2018-04-11",
    "publication-date": "2018-04-11",
    "title": "Watch for EPA Compliance Gaps",
    "topic-group": {
      "topic-1": "Energy and Efficiency, Sustainability"
    },
    "url": "https:\/\/businessofficermagazine.lndo.site\/features\/watch-for-epa-compliance-gaps\/",
    "sidebar-group-1": {
      "content": "The following resources include compliance guides and other support that can help campuses get on track to create a strong compliance structure.\n\nCampus ERC (a collaboration among NACUBO, C2E2, CSHEMA, APPA, and the EPA)\nState Regulations Resource Locator\nCampus Safety Health and Environmental Management [truncated for this example]",
      "title": "EPA Compliance Tools"
    },
    "pullquote-group-1": {
      "author": null,
      "content": "It is imperative that business officers work with faculty, departmental chairs, or fellow administrators to ensure a strong system is in place for ongoing compliance in all campus areas."
    }
  }
}
```

===

### Nacubo Notes

Endpoint: `/wp-json/bom/v1/nacubo-notes` 

Schema:

```
{
  "nacubo-notes-##": {
    "abstract": null,
    "author": null,
    "category": string,
    "content": null,
    "image": null,
    "issue": string,
    "modified-date": string,
    "publication-date": string,
    "title": string,
    "topic-group": null,
    "url": string,
    "content-group-#": {
      "abstract": string,
      "author": string,
      "category": string,
      "content": string,
      "image-url": string,
      "issue": string,
      "modified-date": string,
      "publication-date": string,
      "title": string,
      "topic-group": null,
      "url": string
    }
  }
}
````

Response example (long text strings are truncated in this example):

```json
{
  "nacubo-notes-29": {
    "abstract": null,
    "author": null,
    "category": "nacubo-notes",
    "content": null,
    "image": null,
    "issue": "September 2016",
    "modified-date": "2018-04-23",
    "publication-date": "2018-04-11",
    "title": "NACUBO Notes, September 2016",
    "topic-group": null,
    "url": "https:\/\/businessofficermagazine.lndo.site\/nacubo-notes\/nacubo-notes-september-2016\/",
    "content-group-1": {
      "abstract": "Each year, NACUBO honors individual and institutional excellence through its awards program. The 2016 recipients were recognized in July, during the annual meeting in Montr\u00e9al, at a ceremony supported by Sodexo.\nHunter, Yestramski Receive Distinguished B ...",
      "author": "NACUBO Editorial Team",
      "category": "nacubo-notes",
      "content": "Each year, NACUBO honors individual and institutional excellence through its awards program. The 2016 recipients were recognized in July, during the annual meeting in Montr\u00e9al, at a ceremony supported by Sodexo.\nHunter, Yestramski Receive Distinguished Business Officer [truncated for this example]",
      "image-url": null,
      "issue": "September 2016",
      "modified-date": "2018-04-23",
      "publication-date": "2018-04-11",
      "title": "2016 NACUBO Award Recipients",
      "topic-group": null,
      "url": "https:\/\/businessofficermagazine.lndo.site\/nacubo-notes\/nacubo-notes-september-2016\/#2016-nacubo-award-recipients"
    },
    "content-group-2": {
      "abstract": "In light of recent student demonstrations at institutions across the country, there is a significant need for increased dialogue and meaningful action on the part of campus leadership. In the recent webcast, The CBO\u2019s Role in Diversity and Inclusion on  ...",
      "author": "NACUBO Editorial Team",
      "category": "nacubo-notes",
      "content": "In light of recent student demonstrations at institutions across the country, there is a significant need for increased dialogue and meaningful action on the part of campus leadership. In the recent webcast, The CBO\u2019s Role in Diversity and Inclusion on Campus, Gerald [truncated for this example]",
      "image-url": null,
      "issue": "September 2016",
      "modified-date": "2018-04-23",
      "publication-date": "2018-04-11",
      "title": "Recent Webcast on Diversity and Inclusion",
      "topic-group": null,
      "url": "https:\/\/businessofficermagazine.lndo.site\/nacubo-notes\/nacubo-notes-september-2016\/#recent-webcast-on-diversity-and-inclusion"
    }
  }
}
```
