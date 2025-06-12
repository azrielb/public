#pragma once
#include <map>
#include <queue>
#include "HuffmanNode.h"

class HuffmanTree
{
private:
	class HuffmanNodeComparing {
	public:
		bool operator()(HuffmanNode * a, HuffmanNode * b) {
			return *a > *b;
		}
	};
	typedef std::priority_queue<HuffmanNode *, std::vector<HuffmanNode *>, HuffmanNodeComparing> HuffmanNode_priority_queue;
	// fields
	std::map<char, unsigned long long> letters;
	HuffmanNode * root;
	// build the HuffmanTree from priority queue of "HuffmanNode" pointers
	void buildTree(HuffmanNode_priority_queue & strings);
	// build the the table from the HuffmanTree
	void buildTable();
	void treeToTable(HuffmanNode * HuffmanNode, unsigned long long code = 1);
public:
	HuffmanTree();
	virtual ~HuffmanTree();
	unsigned long long encodeLetter(char letter);
	char decodeLetter(unsigned long long code);
	std::string encodeText(std::string text);
	std::string decodeText(std::string coded);
};
