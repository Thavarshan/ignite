# Minerva Specifications (Backend API)

#### Customer Journey – Report Order

- [x] Clients can easily order a Crime or RTA report, following the same logic as in the current RPR system.
  - [x] Police Force identified by location.
  - [x] Reports that can be ordered are displayed for customer selection (e.g., Full Report, Third Party Details, etc.), including the ability to order additional items at the point of ordering.
  - [x] Relevant fields required for that request are displayed and the client is asked to enter information into the form.
  - [x] Client can enter notes and further information.
    - [x] These should be easy for NW staff to acknowledge with a simple button.
  - [ ] Cost of report is displayed, if and when known.
  - [ ] Average turnaround for that report type with that force can be displayed.

#### Customer Journey – Report Received

- [ ] PDF reports can be uploaded for the client, with the capacity to upload other items of evidence (photos, videos, statements, etc.).
- [ ] Client receives an email notification of new report availability.
- [ ] Key details from the report can be displayed on the screen without the need to open the full report.
  - [ ] Vehicles involved.
  - [ ] Drivers & passengers in vehicle.
  - [ ] Witnesses.
- [ ] Client can download the full report.
- [ ] Client can order additional available evidence at the click of a button.
  - [ ] NW staff would need the ability to enter what is available and the cost on a case-by-case basis in the backend.

#### Customer Journey – Order Status

- [ ] Clients should be able to see a list of all their requests & current status.
- [ ] Admins should be able to see all requests.
- [ ] It should be possible to configure visibility of requests for either all team members or just their own.
- [ ] Client can search requests by reference number.
- [ ] Client has the ability to swap who ‘owns’ the case.
- [ ] MI data available for display in front end, such as ‘requests outstanding’, etc.

#### Available Reports

- [ ] Each customer company should be able to see a list of ‘available reports’ – these are reports we have on file featuring their policyholders that they haven’t ordered.
- [ ] They should be able to ‘order’ them there and then, and get instant access – and charged.
- [ ] NW staff will need to be able to manage in the backend reports ‘available’ for each client.
- [ ] At the point of order – if a client enters a VRN that we already have on file – alert them to the possibility of the report already existing; they should be able to order and access that if they decide it is a match.

#### Virtual Witness

- [ ] Available (now delivered in toolsuite).
- [ ] VW data should be auto-scanned for matches whenever a VRN is entered into the system.

#### General Build

- [ ] Built with a view that eventually it will be possible to order more NW services relating to each case.
- [ ] User journey should be case-focused, so any report ordered is part of a case – and additional materials are also within this case and easy to navigate between if ordered from the system.
- [ ] Designed with eventually facilitating the order to NW ‘Managed Services’ (profiling etc), and ToolSuite products – to be worked on at a later date.

#### Billing

- [ ] System needs to be able to auto-generate bordereau invoices for clients.
- [ ] Redesigned system to facilitate client billing, with client invoice spreadsheet automatically generated on a regular basis, or at the push of a button.

#### Backend – NW Users

- [ ] Backend should have a means of importing emails so that we can store an email trail related to each case and easily view them.
- [ ] Actioning List – a list of tasks showing emails or notes for anything set as ‘needs to be actioned’ by NW staff.

#### Police User

- [ ] Simply has the ability to upload a report and they enter a code for it to automatically load to the correct file.
- [ ] On upload, a notification should go to NW; NW follows the processes in workflow on uploading a report – and then release report to client.
