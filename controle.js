'use strict';
function main() {
    const types = ['c1', 'c2', 'c3'].some((id) => document.getElementById(id).checked);
    const ageEnabled = document.getElementById('c4').checked;
    const age = document.getElementById('i').value;
    if (!types) { alert('Sélectionnez au moins un type d’animal.'); return false; }
    if (ageEnabled && (age === '' || !Number.isInteger(Number(age)) || Number(age) < 0 || Number(age) > 100)) { alert('Indiquez un âge maximum valide.'); return false; }
    return true;
}
function main2() {
    const username = document.getElementById('name').value.trim();
    const password = document.getElementById('password').value;
    const animal = document.getElementById('animal_id').value;
    const gcd = document.getElementById('pgcd').value;
    if (username.length < 1 || username.length > 50 || password.length < 1 || !animal || gcd === '' || Number(gcd) < 1) { alert('Complétez tous les champs correctement.'); return false; }
    return true;
}
