/** Alpine helpers for Filament-style conditional callout fields. */
document.addEventListener("alpine:init", () => {
  Alpine.data("calloutForm", (initial) => ({
    mobilised: String(initial.mobilised ?? "0"),
    medical_facility: String(initial.medical_facility ?? "0"),
    attended: String(initial.attended ?? "0"),
    ohca_at_scene: String(initial.ohca_at_scene ?? "0"),
    bystander_cpr: String(initial.bystander_cpr ?? "0"),
    rosc_achieved: String(initial.rosc_achieved ?? "0"),
    patient_transported: String(initial.patient_transported ?? "0"),
    source_of_aed: initial.source_of_aed ?? "",
    number_of_shocks_given: initial.number_of_shocks_given ?? "0",
    isYes(v) {
      return String(v) === "1";
    },
    showMedicalFacility() {
      return this.mobilised !== "" && !this.isYes(this.mobilised);
    },
    showMobilisedFields() {
      return this.isYes(this.mobilised);
    },
    showOhcaFields() {
      return this.showMobilisedFields() && this.isYes(this.attended);
    },
    showCardiacDetail() {
      return this.showOhcaFields() && this.isYes(this.ohca_at_scene);
    },
    showPatientTransported() {
      if (!this.isYes(this.attended)) return false;
      return !this.isYes(this.ohca_at_scene) || this.isYes(this.rosc_achieved);
    },
  }));
});
