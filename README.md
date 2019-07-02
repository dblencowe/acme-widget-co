# ACME Widget Co Sales Total Calculator

## Tasklist
- [x] Create git repository, initialize composer project and add initial ERD
- [x] Write test harness
- [x] Implement initial interface for required basket methods
- [x] Implement Entity Models
- [x] Implement Basket
- [ ] Finish off documentation

## Assumptions
- All data coming in via fixtures is valid, thus no validation of data structure is required
- That special offers are all Buy X Product and get X off
- To avoid going in to the depts of currency calculations currency is stored in it's smallest form and we always round down (As this appears to be what happens in the examples).
- That special cannot stack, once a valid special offer has been found it is applied and the process stopped
- For production use the Delivery cost system would want to be built in a more robust manner