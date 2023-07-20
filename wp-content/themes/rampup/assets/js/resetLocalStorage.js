addMeetingFormLS = window.localStorage.getItem("add-meeting-form");

pathname = location.pathname;

if (
  (pathname == "/add-meeting-form/" || pathname == "/add-meeting-thanks/") &&
  addMeetingFormLS
) {
  location.href = "/";
} else {
  window.localStorage.removeItem("add-meeting-form");
}
