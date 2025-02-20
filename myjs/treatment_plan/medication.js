// Function to open the Add Medication Modal
function openAddMedModal(treatment = {}) {
    // Populate fields if updating medication
    document.querySelector('[name="visit_day"]').value = treatment.visit_day || '';
    document.querySelector('[name="start_date"]').value = treatment.start_date || '';
    document.querySelector('[name="end_date"]').value = treatment.end_date || '';
    document.querySelector('[name="medication_name"]').value = treatment.medication_name || '';
    document.querySelector('[name="medication_action"]').value = treatment.medication_action || '';
    document.querySelector('[name="dose_description"]').value = treatment.dose_description || '';
    document.querySelector('[name="dose_duration"]').value = treatment.dose_duration || '';
    document.querySelector('#treatment_id').value = treatment.treatment_id || '';
    document.querySelector('#medication_id').value = treatment.medication_id || '';

    // Open the modal
    $('#addMedModal').modal('show');
}

// Add Medication Button
const addButton = document.querySelector('#addMedButton');
if (addButton) {
    addButton.addEventListener('click', () => openAddMedModal());
}

// Update Medication Links
const updateLinks = document.querySelectorAll('.updateMedLink');
updateLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        const treatment = JSON.parse(link.getAttribute('data-treatment'));
        openAddMedModal(treatment);
    });
});
