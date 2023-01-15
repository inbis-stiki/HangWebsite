<?php
namespace App;

class TargetUser {
    public function getUser(){
        // target bulanan user
        $prods = [
            'UST'       =>  80,
            'NONUST'    => 660,
            'Seleraku'  => 120,
            'Rendang'   => 250,
            'Geprek'    => 250
        ];

        $acts = [
            'UB'        => 12,
            'PS'        => 14,
            'Retail'    => 14
        ];

        return ['prods' => $prods, 'acts' => $acts];
    }
    public function getRegional(){
        // target bulanan per regional
        // (target * 3[total apo/spg dalam 1 area]) * 14[total area dalam 1 regional]
        $prods = [
            'UST'       => ($this->getUser()['prods']['UST'] * 3) * 14,
            'NONUST'    => ($this->getUser()['prods']['NONUST'] * 3) * 14,
            'Seleraku'  => ($this->getUser()['prods']['Seleraku'] * 3) * 14,
            'Rendang'   => ($this->getUser()['prods']['Rendang'] * 3) * 14,
            'Geprek'    => ($this->getUser()['prods']['Geprek'] * 3) * 14
        ];
        

        // (target * 3[total apo/spg dalam 1 area]) * 14[total area dalam 1 regional]
        $acts = [
            'UB'        => $this->getUser()['acts']['UB'] * 14,
            'PS'        => (($this->getUser()['acts']['PS'] * 3) * 10) * 25,
            'Retail'    => (($this->getUser()['acts']['Retail'] * 3) * 10) * 25
        ];

        return ['prods' => $prods, 'acts' => $acts];
    }
    public function getAsmen(){
        // target bulanan per asmen
        // (target * 2[total asmen]
        $prods = [
            'UST'       => $this->getRegional()['prods']['UST'] * 2,
            'NONUST'    => $this->getRegional()['prods']['NONUST'] * 2,
            'Seleraku'  => $this->getRegional()['prods']['Seleraku'] * 2,
            'Rendang'   => $this->getRegional()['prods']['Rendang'] * 2,
            'Geprek'    => $this->getRegional()['prods']['Geprek'] * 2
        ];
        

        $acts = [
            'UB'        => $this->getRegional()['acts']['UB'] * 2,
            'PS'        => $this->getRegional()['acts']['PS'] * 2,
            'Retail'    => $this->getRegional()['acts']['Retail'] * 2
        ];

        return ['prods' => $prods, 'acts' => $acts];
    }
}