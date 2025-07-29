# 🔐 COMPTES UTILISATEURS IFRAN - SYSTÈME DE PRÉSENCE

## 🔑 COMPTE ADMINISTRATEUR (PRIORITÉ)
**Commencez toujours par ce compte pour configurer le système**

- **Email:** admin@ifran.com
- **Mot de passe:** admin123
- **Nom:** Admin System
- **Accès:** COMPLET - Peut tout gérer dans l'application

---

## 📋 LISTE COMPLÈTE DES UTILISATEURS

### 🔑 Administrateur
- **Email:** admin@ifran.com
- **Mot de passe:** admin123
- **Nom:** Admin System
- **Permissions:** Accès complet, gestion de tous les utilisateurs et données

### 👨‍💼 Coordinateur Pédagogique
- **Email:** biniflora@ifran.com
- **Mot de passe:** coord123
- **Nom:** Flora Bini
- **Permissions:** 
  - Gestion des emplois du temps
  - Saisie présences E-learning et Workshop
  - Justification des absences
  - Création/modification/annulation de cours
  - Accès aux statistiques globales

### 👨‍🏫 Enseignant
- **Email:** adoh@ifran.com
- **Mot de passe:** prof123
- **Nom:** Jean Marie Adoh
- **Permissions:**
  - Vue de son emploi du temps personnel
  - Saisie présences cours présentiel (2 semaines max)
  - Accès aux statistiques de ses cours
  - Notifications d'absences excessives

### 👨‍🎓 Étudiant
- **Email:** konejean@gmail.com
- **Mot de passe:** etud123
- **Nom:** Jean KONE
- **Permissions:**
  - Consultation emploi du temps de sa classe
  - Vue de ses absences (justifiées/non justifiées)
  - Consultation uniquement

### 👨‍👩‍👧‍👦 Parent
- **Email:** etudiant@test.com
- **Mot de passe:** parent123
- **Nom:** John Doe
- **Permissions:**
  - Consultation emploi du temps des classes de ses enfants
  - Vue des absences de ses enfants
  - Consultation uniquement

---

## 🚀 INSTRUCTIONS DE CONNEXION

1. **URL de l'application:** http://127.0.0.1:8003
2. **Identifiant:** Utilisez l'email complet
3. **Mot de passe:** Voir la liste ci-dessus

## ⭐ ORDRE DE TEST RECOMMANDÉ

1. **🔑 ADMIN** (admin@ifran.com) - admin123
   - Testez d'abord les fonctions d'administration
   - Créez/gérez les données de base

2. **👨‍💼 COORDINATEUR** (biniflora@ifran.com) - coord123
   - Testez la gestion des emplois du temps
   - Testez la saisie des présences E-learning/Workshop

3. **👨‍🏫 ENSEIGNANT** (adoh@ifran.com) - prof123
   - Testez la vue enseignant de l'emploi du temps
   - Testez la saisie des présences présentiel

4. **👨‍🎓 ÉTUDIANT** (konejean@gmail.com) - etud123
   - Testez la vue étudiant
   - Vérifiez l'affichage des absences

5. **👨‍👩‍👧‍👦 PARENT** (etudiant@test.com) - parent123
   - Testez la vue parent
   - Vérifiez l'accès aux données des enfants

---

## 🎯 FONCTIONNALITÉS CLÉS PAR RÔLE

### Administrateur
- ✅ Gestion complète des utilisateurs
- ✅ Accès à toutes les données
- ✅ Configuration du système
- ✅ Supervision globale

### Coordinateur Pédagogique
- ✅ Création/modification emplois du temps
- ✅ Gestion cours E-learning et Workshop
- ✅ Justification des absences
- ✅ Notifications absences excessives
- ✅ Statistiques globales

### Enseignant
- ✅ Emploi du temps personnel
- ✅ Présences cours présentiel (délai 2 semaines)
- ✅ Statistiques de ses cours
- ✅ Notifications étudiants en difficulté

### Étudiant/Parent
- ✅ Consultation emploi du temps
- ✅ Liste des absences (justifiées/non justifiées)
- ✅ Vue par type de cours
- ✅ Interface dédiée

---

## ⚠️ NOTES IMPORTANTES

- **Sécurité:** Ces mots de passe sont pour les tests uniquement
- **Production:** Changez tous les mots de passe en production
- **Délais:** Les enseignants ont 2 semaines pour saisir les présences
- **Seuil critique:** 30% d'absence = notification automatique
- **Types de cours:** Présentiel (Enseignant), E-learning/Workshop (Coordinateur)

---

## 📊 STATISTIQUES ACTUELLES

- **Total utilisateurs:** 5
- **Administrateurs:** 1
- **Coordinateurs:** 1  
- **Enseignants:** 1
- **Étudiants:** 1
- **Parents:** 1

---

**Généré le:** 29 juillet 2025
**Version du système:** IFRAN v1.0 - Conforme au brief spécifications
