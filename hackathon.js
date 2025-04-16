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
  candidatures.forEach(candidature => {
    console.log("ID: "+candidature.id+"\n"
                +"Nom: "+candidature.nom+"\n"
                +"Age: "+candidature.age+"\n"
                +"Projet: "+candidature.projet+"\n"
                +"Statut: "+candidature.statut);
  });
}
function validerCandidature(id) {
  const search = candidatures.find(candidature=>candidature.id===id);
  (search)? candidature.statut="validée" : console.log("non trouvée");
}
function rejeterCandidature(id) {
  const search = candidatures.find(candidature=>candidature.id===id);
  (search)? candidature.statut="rejetée" : console.log("non trouvée");
}
function rechercherCandidat(nom) {
  const resultats = candidatures.filter(candidature => candidature.nom.includes(nom.toLowerCase()));
  resultats.forEach(candidature => {
    console.log("ID: "+candidature.id+"\n"
                +"Nom: "+candidature.nom+"\n"
                +"Age: "+candidature.age+"\n"
                +"Projet: "+candidature.projet+"\n"
                +"Statut: "+candidature.statut);});
}
function filtrerParStatut(statut) {
  const resultats = candidatures.filter(candidature=>candidature.statut===statut);
  if(!resultats){
    console.log("Candidat with statut "+statut+": ");
    resultats.forEach(candidature => {
      console.log("ID: "+candidature.id+"\n"
                  +"Nom: "+candidature.nom+"\n"
                  +"Age: "+candidature.age+"\n"
                  +"Projet: "+candidature.projet);
    });
  }else console.log("Pas de candidat with "+statut);
}
function statistiques() {
    const total = candidatures.length;
    const valide = candidatures.filter(c => candidature.statut === "validée").length;
    const rejete = candidatures.filter(c => candidature.statut === "rejetée").length;
    const enAttente = candidatures.filter(c => candidature.statut === "en attente").length;
  
    console.log("Statistiques: \n");
    console.log(`Total des candidatures : `+total);
    console.log(`Validées : `+valide);
    console.log(`Rejetées : `+rejete);
    console.log(`En attente : `+enAttente);
}
function trierParNom() {

}
function trierParAge(desc = false) {

}
function topProjets(motCle) {

}
function resetSysteme() {
    candidatures = [];
    console.log("Empty");
}

console.log(ajouterCandidature("abde", "23","Cyber SOC"));
console.log(ajouterCandidature("FrJ", "24","JS"));
console.log(afficherToutesLesCandidatures());
console.log(filtrerParStatut("en attente"));
console.log(rechercherCandidat("ABDE"));
console.log(validerCandidature(1));
console.log(rejeterCandidature(1));
console.log(topProjets("C"));
console.log(statistiques());
console.log(trierParNom());
console.log(trierParAge());
//console.log(resetSysteme());