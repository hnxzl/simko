<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Vehicle;

class FlatsarSeeder extends Seeder
{
    public function run(): void
    {
        /* ===========================
         *  roles
         * =========================== */
        $users = [
            // Admin
            ['ADMIN', null, 'admin@simko.com', null, 'admin'],
            // HRD (mapped from Kepala Sumber Daya)
            ['AFRISAL SOELAIMAN S. E.', '198508292005021001', 'afrisal.soelaiman@simko.com', '082199469344', 'hrd'],
            // Managers (mapped from Ketua Tim)
            ['DIRMAN SANDEWA S.AP.', '198908172009121001', 'dirman.sandewa@simko.com', '082189417889', 'manager'],
            ['RICKY MALLAWAN', '198912292009121001', 'ricky.mallawan@simko.com', '082139560168', 'manager'],
            ['DIMAS TRIATMOJO', '198807232009121001', 'dimas.triatmojo@simko.com', '0811416664', 'manager'],
            ['MARIO PASKAHLIS RUBAK ALLO', '198704102009121003', 'mario.allo@simko.com', '082192414089', 'manager'],
            ['IMAM TAUFIQ', '199409132015031001', 'imam.taufiq@simko.com', '082188222298', 'manager'],
            // BoD
            ['DJOKO IRAWAN', '197606112025211028', 'djoko.irawan@simko.com', '081317772622', 'bod'],
            // Karyawan (mapped from Pegawai)
            ['ADIANSYAH', '199004122010121001', 'adiansyah@simko.com', '085240697154', 'karyawan'],
            ['SAYUDI YUSUF GINANJAR', '199011282009121001', 'sayudi.ginanjar@simko.com', '085215705557', 'karyawan'],
            ['MUHTAR', '199102122010121001', 'muhtar@simko.com', '082271093503', 'karyawan'],
            ['TAKDIR ZULKIFLI', '199104282010121001', 'takdir.zulkifli@simko.com', '082292003123', 'karyawan'],
            ['BAHTIAR', '198712282010121003', 'bahtiar@simko.com', '085242868209', 'karyawan'],
            ['JABBAR', '198904262010121005', 'jabbar@simko.com', '082333666614', 'karyawan'],
            ['YULIA SARI PUTRI BRASILIA', '199407182015032001', 'yulia.brasilia@simko.com', '082257809534', 'karyawan'],
            ['ANDI SAFRULLAH SYARIYAMTO', '199009192015031003', 'andi.syariyamto@simko.com', '085255856826', 'karyawan'],
            ['RYAN R KATILI', '199512012015031001', 'ryan.katili@simko.com', '085337274381', 'karyawan'],
            ['FERAWATI DANI A.Md.', '199211092020122002', 'ferawati.dani@simko.com', '082349122292', 'karyawan'],
            ['MOHAMAD ANDI MAHARDIKA', '199603122015031001', 'mohamad.mahardika@simko.com', '081243756142', 'karyawan'],
            ['HARIYANTO', '199108112015031003', 'hariyanto@simko.com', '081294124235', 'karyawan'],
            ['OGI TRI KURNIAWAN', '199509282015031001', 'ogi.kurniawan@simko.com', '085298082211', 'karyawan'],
            ['TAHRIZAL A. RAMADANI', '199601212017121003', 'tahrizal.ramadani@simko.com', '082292714609', 'karyawan'],
            ['IRVAN RAHARJAN', '199704042017121007', 'irvan.raharjan@simko.com', '082259060936', 'karyawan'],
            ['MOH.RIVAI', '199701162017121003', 'moh.rivai@simko.com', '082271348332', 'karyawan'],
            ['ALI FAJAR ZODIK', '199706062017121006', 'ali.zodik@simko.com', '085256500364', 'karyawan'],
            ['MANDASARI HANINGTYAS', '199505012020122004', 'mandasari.haningtyas@simko.com', '089638888558', 'karyawan'],
            ['MOH. AGUS BUDIMAN', '199708162020121001', 'moh.budiman@simko.com', '082259833887', 'karyawan'],
            ['ARASPATI PUTRA PERWIRA UTAMA', '200205102025061001', 'araspati.utama@simko.com', '082191261787', 'karyawan'],
            ['IMRAN AMINULLAH', '200109222025061003', 'imran.aminullah@simko.com', '087837852799', 'karyawan'],
            ['SITI NURHANISA', '200605212025062001', 'siti.nurhanisa@simko.com', '083878573179', 'karyawan'],
            ['FAJARUDDIN', '200004142025061005', 'fajaruddin.fajaruddin@simko.com', '082266871815', 'karyawan'],
            ['KURNIA', '200403142025062001', 'kurnia@simko.com', '082296542131', 'karyawan'],
            ['MUH. FAJAR ARFAH', '200401312025061001', 'muh.arfah@simko.com', '085341807331', 'karyawan'],
            ['CARSTEN GLEEN HASAN', '200201242025061004', 'carsten.hasan@simko.com', '085238546335', 'karyawan'],
            ['MARDIN', '200209052025061003', 'mardin@simko.com', '082292052549', 'karyawan'],
            ['ALDI SONO', '200209212025061001', 'aldi.sono@simko.com', '083826166247', 'karyawan'],
            ['ERLANGGA SATRIA PUTRA WARDANA', '200502162025061002', 'erlangga.wardana@simko.com', '087817594635', 'karyawan'],
            ['ANDI MAGFIRATUL MURADIFAH', '200105092025062003', 'andi.muradifah@simko.com', '0895601846472', 'karyawan'],
            ['HAIKAL ANANDA PRATAMA', '200210012025061001', 'haikal.pratama@simko.com', '083863222189', 'karyawan'],
            ['MUHAMMAD ARFIAN PRATAMA', '200208212025061002', 'arfian.pratama@simko.com', '082142560229', 'karyawan'],
            ['LA ODE KAHAR DAFIQ', '200404182025061001', 'la.dafiq@simko.com', '081341912635', 'karyawan'],
            ['FIRGIANSYAH', '200207212025061002', 'firgiansyah.firgiansyah@simko.com', '085117404797', 'karyawan'],
            ['FAHRIL IRFAN', '200110142025061001', 'fahril.irfan@simko.com', '081236713432', 'karyawan'],
            ['YUSUF PUTRA PRADANA', '200104302025061005', 'yusuf.pradana@simko.com', '082297274619', 'karyawan'],
            ['ARIO ADI SATRIA WIBOWO', '200302272025061002', 'ario.wibowo@simko.com', '0895605132667', 'karyawan'],
            ['HABRULLAH', '198506282025211044', 'habrullah@simko.com', '085299461558', 'karyawan'],
            ['MOH.RIYADIN FIRLY', '200002152020121004', 'moh.syach@simko.com', '082386033578', 'karyawan'],
            ['ASRUL RUSANI', '198905302015031003', 'asrul.rusani@simko.com', '085398888686', 'karyawan'],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(
                ['email' => $u[2]],
                [
                    'name' => $u[0],
                    'NIP' => $u[1],
                    'phone' => $u[3],
                    'role' => $u[4],
                    'institution' => null,
                    'password' => Hash::make('password'),
                    'email_verified_at' => Carbon::now(),
                ]
            );
        }

        /* ===========================
         *  Insert Vehicles
         * =========================== */
        $vehicles = [
            ['Rescue Car Double cabin 01', 'DN 8870 A', 3020101003],
            ['Rescue Car Double cabin 02', 'B 9425 POR', 3020101004],
            ['Rescue Car Double cabin Hilux', 'B 9228 PSE', 3020105129],
            ['Rescue Car Carrie Commob', 'B 1577 PQR', 3020105060],
            ['Rescue Car Carrie Ambulance', 'B 1072 PQR', 3020105061],
            ['Truck Personil 03', 'B 9599 PQR', 3020105062],
            ['Truck Personil 06', 'B 9986 POQ', 3020105063],
            ['Rescue Truck', 'B 9091 PQR', 3020105064],
            ['Truck Pengangkut ATV', 'B 9033 POR', 3020101005],
        ];

        foreach ($vehicles as $v) {
            Vehicle::updateOrCreate(
                ['kode_bmn' => $v[2]],
                [
                    'name' => $v[0],
                    'plat_nomor' => $v[1],
                    'status' => 'available',
                ]
            );
        }
    }
}
