# Web Solana Node Manager (WSNM)

Secure web interface for Solana validator identity transfers and failover

**Secure. Mobile. In control.**

Web Solana Node Manager (WSNM) extends **Solana Node Manager (SNM)** with a simple and secure web interface.  
It allows you to manage **validator identity transfers** remotely — from your desktop, tablet, or even phone.

---

## Overview

**WSNM** acts as a secure bridge between your browser and Solana Node Manager.  
Validator keys remain encrypted and safely stored inside **SNM**, while WSNM only sends verified commands  
to transfer your validator identity between available Solana servers.


```text
┌─────────┐        ┌──────────────┐        ┌──────────────┐
│ Browser │ <----> │  WSNM Server │ <----> │  SNM Server  │
└─────────┘        └──────────────┘        └──────────────┘
                                             ▲          ▲
                                             │          │
                                             │          │
                                             v          v
                               ┌────────────────┐    ┌────────────────┐
                               │ Solana Primary │    │ Solana Spare   │
                               │    Validator   │    │    Validator   │
                               └────────────────┘    └────────────────┘
```

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

### Open Source

Use the hosted version or deploy your own.  
WSNM is fully open-source and available on GitHub.

---

## Getting Started

To connect your validator to WSNM:

1. [**Install Solana Node Manager (SNM)**](https://github.com/StakeNode777/solana-node-manager) with the **SNM External Interface** on a separate server.  
2. **Set up WSNM** — either  
   - install it on your own server, **or**  
   - use the hosted version at [wsnm.stakenode777.com](https://wsnm.stakenode777.com).  
3. **Add your validator** and manage identity transfers directly from the dashboard.

---

## Installation

We provide installation steps for Ubuntu/Debian-based systems.
However, the project can also run on any OS where **git** and **Docker** are available.

During the startup process, the script launches a full **LAMP stack inside Docker containers**.

**To install and run:**

```bash
sudo apt update && sudo apt upgrade -y && sudo apt install -y git docker.io docker-compose-v2
sudo usermod -aG docker $USER
```

Logout and login again, then continue.

```bash
git clone https://github.com/StakeNode777/web-solana-node-manager
cd web-solana-node-manager
bash start.sh
```

After the build completes, the application will be available at:

- your provided domain name, or
- 👉 http://localhost:8080


**To stop:**

```bash
bash stop.sh
```

---

## License

This project is licensed under the [MIT License](LICENSE).

---

## Community & Support

- Website: [https://wsnm.stakenode777.com](https://wsnm.stakenode777.com)  
- GitHub: [https://github.com/StakeNode777/web-solana-node-manager](https://github.com/StakeNode777/web-solana-node-manager)  
- Twitter: [@StakeNode777](https://twitter.com/StakeNode777)

---

**Web Solana Node Manager (WSNM)** — manage your Solana validator securely, from anywhere.
