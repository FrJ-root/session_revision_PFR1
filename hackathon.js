let candidatures = [];
const id=candidatures+1;

function ajouterCandidature(nom, age, projet) {
  const candidature = {
    id: id,
    nom: nom,
    age: age,
    projet: projet,
    statut: "en attente"
  };
  candidatures.push(candidature);
}
function afficherToutesLesCandidatures() {
  console.log('Toutes les candidatures :');
  candidatures.forEach(candidature => {
    console.log("ID: "+candidature.id+"\n"
                +"Nom: "+candidature.nom+"\n"
                +"Age: "+candidature.age+"\n"
                +"Projet: "+candidature.projet+"\n"
                +"Statut: "+candidature.statut);});
}
function validerCandidature(id) {
  const search = candidatures.find(candidature=>candidature.id===id);
  (search)? candidature.statut="validee" : console.log("non trouvée");
}
function rejeterCandidature(id) {
  const search = candidatures.find(candidature=>candidature.id===id);
  (search)? candidature.statut="rejetee" : console.log("non trouvée");
}
function rechercherCandidat(nom) {
  const resultats = candidatures.filter(candidature => candidature.nom.toLowerCase().includes(nom.toLowerCase()));
  resultats.forEach(candidature => {
    console.log("ID: "+candidature.id+"\n"
                +"Nom: "+candidature.nom+"\n"
                +"Age: "+candidature.age+"\n"
                +"Projet: "+candidature.projet+"\n"
                +"Statut: "+candidature.statut);});
}




console.log(ajouterCandidature("abde", "23","Cyber SOC"));
console.log(afficherToutesLesCandidatures());
console.log(validerCandidature(1));
console.log(rejeterCandidature(1));
console.log(rechercherCandidat("AbDe"));







