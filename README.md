W-SAGE
======
#Web tool for Spatial Analysis of GEodata

##Implementação referente ao Trabalho de Conclusão do Curso de Ciência da Computação - UFRRJ realizado em Dezembro de 2014.

###Sobre

Este sistema foi implementado para ser uma *Ferramenta Web de análise espacial de dados geográficos*, com funções como consultas espaciais com ou sem restrição de região (polígono), visualização em formato de mapa de calor (Heatmap) ou por geolocalização (pontos) ou por gráficos entre outros.

###Desempenho
O projeto está sendo construído visando o desempenho principalmente na parte de visualização dos dados no front-end.

É utilizado o algoritmo do KDE (Kernel Density Estimation) para alcançar resultados mais suavizados na hora de criar o mapa de calor.

Esta implementação está fazendo uso de paralelização para conseguir melhores resultados com o KDE, a paralelização está sendo feita automaticamente de acordo com o número de pontos a serem buscadas em cada consulta.

Outra parte no qual o desempenho é levado em conta é em relação a visualização dos pontos, que neste projeto está sendo clusterizado e rasterizado, evitando assim o travamento da página mesmo com uma boa quantidade de pontos (por volta dos 20.000).