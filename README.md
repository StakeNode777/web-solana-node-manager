# Web Solana Node Manager (WSNM)

External Web App for Solana Node Manager — instant validator identity transfer in a few clicks

**Secure. Mobile. In control.**

Web Solana Node Manager (WSNM) extends **Solana Node Manager (SNM)** with a simple and secure web interface.  
It allows you to manage **validator identity transfers** remotely — from your desktop, tablet, or even phone.

---

## Overview

**WSNM** acts as a secure bridge between your browser and Solana Node Manager.  
Validator keys remain encrypted and safely stored inside **SNM**, while WSNM only sends verified commands  
to transfer your validator identity between available Solana servers.


┌─────────┐        ┌──────────────┐        ┌──────────────┐ 
│ Browser │ <----> │  WSNM Server │ <----> |  SNM Server  |
└─────────┘        └──────────────┘        └──────────────┘ 
                                             ▲          ▲
                                             │          │
                                             │          │
                                             v          v
                               ┌────────────────┐    ┌────────────────┐  
                               │ Solana Primary │    │ Solana Spare   │
                               │    Validator   │    │    Validator   │
                               └────────────────┘    └────────────────┘

---

## Key Features

### Manage from your phone

Easily transfer your validator identity while traveling — from the airport, taxi, or even the beach.  
The WSNM interface is fully responsive and optimized for mobile devices.

### Safe delegation

Grant access to a trusted system administrator when you’re away.  
They can manage node transfers through WSNM, or in emergency cases access your validator server  
(if it doesn’t store your identity).  
Your validator identity key always stays encrypted inside **SNM**, accessible only to you.

### Multiple validators

Manage several validators across **Mainnet** and **Testnet** in one unified dashboard.

### Open Source [WILL BE SOON]

Use the hosted version or deploy your own.  
WSNM is fully open-source and available on GitHub.
